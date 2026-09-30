@php($settings = \App\Models\Setting::values())
<x-layouts::app :title="__('site.nav.contact')">
    <x-page-hero :eyebrow="__('site.form.eyebrow')" :title="__('site.form.title')"
                 image="/media/works/pink-balloons/1.webp">
        {{ __('site.form.text') }}
    </x-page-hero>
    <section class="container-x grid gap-10 pb-28 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <livewire:inquiry-form />
        </div>
        <aside class="space-y-4">
            @foreach ([['phone', 'phone', 'tel:'.preg_replace('/[^\d+]/', '', $settings['phone'])], ['mail', 'email', 'mailto:'.$settings['email']], ['pin', 'address', null]] as [$icon, $key, $href])
                <div class="reveal card flex items-center gap-5 p-6">
                    <span class="grid size-12 shrink-0 place-items-center rounded-2xl bg-gold-500/15 text-gold-400"><x-icon :name="$icon" /></span>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-stone-500">{{ __('site.contact.'.$key) }}</p>
                        @if ($href)
                            <a href="{{ $href }}" class="text-lg text-stone-100 hover:text-gold-300">{{ $settings[$key] }}</a>
                        @else
                            <p class="text-lg text-stone-100">{{ $settings[$key] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="reveal card p-6">
                <p class="text-xs uppercase tracking-widest text-stone-500">{{ __('site.contact.follow') }}</p>
                <div class="mt-4 flex gap-3">
                    @if ($settings['instagram'])<a href="{{ $settings['instagram'] }}" target="_blank" rel="noopener" class="grid size-12 place-items-center rounded-full border border-white/10 hover:bg-gold-500 hover:text-ink-950" aria-label="Instagram"><x-social network="instagram" /></a>@endif
                    @if ($settings['facebook'])<a href="{{ $settings['facebook'] }}" target="_blank" rel="noopener" class="grid size-12 place-items-center rounded-full border border-white/10 hover:bg-gold-500 hover:text-ink-950" aria-label="Facebook"><x-social network="facebook" /></a>@endif
                </div>
            </div>
        </aside>
    </section>
</x-layouts::app>
