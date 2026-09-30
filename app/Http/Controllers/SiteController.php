<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Work;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        $heroWorks = Work::published()
            ->with('media')
            ->orderByDesc('is_featured')
            ->latest('event_date')
            ->take(5)
            ->get()
            ->filter(fn (Work $work) => $work->cover()?->type === 'image');

        return view('pages.home', [
            'heroImages' => $heroWorks->map(fn (Work $work) => $work->cover()->src())->values(),
            'services' => Service::where('is_active', true)->orderBy('position')->get(),
            'testimonials' => Testimonial::where('is_active', true)->latest()->get(),
            'stats' => array_filter([
                'events' => (int) Setting::get('stat_events'),
                'guests' => (int) Setting::get('stat_guests'),
                'years' => (int) Setting::get('stat_years'),
            ]),
        ]);
    }

    public function works(): View
    {
        return view('pages.works');
    }

    public function work(Work $work): View
    {
        abort_unless($work->is_published || auth()->user()?->is_admin, 404);

        $work->increment('views');
        $work->load('media', 'category');

        return view('pages.work', [
            'work' => $work,
            'related' => Work::published()->with('media')
                ->whereKeyNot($work->id)
                ->when($work->category_id, fn ($q) => $q->orderByRaw('category_id = ? desc', [$work->category_id]))
                ->latest('event_date')
                ->take(3)
                ->get(),
        ]);
    }

    public function videos(): View
    {
        return view('pages.videos');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function locale(Request $request, string $locale): RedirectResponse
    {
        abort_unless(array_key_exists($locale, config('app.locales')), 404);

        $request->session()->put('locale', $locale);

        return redirect()->to(url()->previous(route('home')))
            ->withCookie(cookie()->forever('locale', $locale));
    }
}
