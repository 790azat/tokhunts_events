<?php

namespace Tests\Feature;

use App\Livewire\Admin\WorkForm;
use App\Livewire\Auth\Register;
use App\Livewire\InquiryForm;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Work;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed(ContentSeeder::class);
    }

    public function test_public_pages_render_in_every_language(): void
    {
        $work = Work::first();

        foreach (['hy' => 'Պորտֆոլիո', 'ru' => 'Портфолио', 'en' => 'Portfolio'] as $locale => $text) {
            $this->get("/lang/{$locale}")->assertRedirect();
            $this->get('/')->assertOk()->assertSee($text);
            $this->get('/works')->assertOk();
            $this->get('/works/'.$work->slug)->assertOk()->assertSee($work->tr('title', $locale));
            $this->get('/videos')->assertOk();
            $this->get('/contact')->assertOk();
        }
    }

    public function test_visitor_can_send_an_inquiry(): void
    {
        Livewire::test(InquiryForm::class)
            ->set('name', 'Ani')
            ->call('submit')
            ->assertHasErrors('phone')
            ->set('phone', '+37499000000')
            ->call('submit')
            ->assertSet('sent', true);

        $this->assertDatabaseHas(Inquiry::class, ['name' => 'Ani', 'status' => 'new']);
    }

    public function test_admin_email_becomes_admin_on_registration(): void
    {
        config(['app.admin_email' => 'owner@tokhunts.am']);

        Livewire::test(Register::class)
            ->set('name', 'Owner')
            ->set('email', 'owner@tokhunts.am')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertRedirect(route('admin.dashboard'));

        $this->assertTrue(User::firstWhere('email', 'owner@tokhunts.am')->is_admin);
    }

    public function test_admin_panel_is_only_for_admins(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        foreach (['', '/works', '/works/create', '/categories', '/services', '/testimonials', '/inquiries', '/users', '/settings'] as $page) {
            $this->actingAs($admin)->get('/admin'.$page)->assertOk();
        }
    }

    public function test_admin_uploads_photos_and_videos(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        Livewire::test(WorkForm::class)
            ->set('title.ru', 'Свадьба Ани и Арама')
            ->set('photos', [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')])
            ->set('videos', [UploadedFile::fake()->create('clip.mp4', 1024, 'video/mp4')])
            ->set('link', 'https://youtu.be/dQw4w9WgXcQ')
            ->call('save')
            ->assertHasNoErrors();

        $work = Work::latest('id')->first();
        $this->assertSame('Свадьба Ани и Арама', $work->tr('title', 'ru'));
        $this->assertSame(['image', 'image', 'video', 'embed'], $work->media->pluck('type')->all());
        Storage::disk('public')->assertExists($work->media[0]->path);
        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0', $work->media[3]->embedUrl());
    }
}
