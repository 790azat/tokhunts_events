@props(['settings'])
<footer class="theme-plum relative overflow-hidden bg-ink-950 pt-24 pb-10 text-stone-300">
    <svg class="absolute inset-x-0 top-0 h-10 w-full text-[#fffaf3]" viewBox="0 0 1440 40" preserveAspectRatio="none" aria-hidden="true"><path fill="currentColor" d="M0 0h1440v14c-120 18-240 26-360 18S840 8 720 8 480 24 360 28 120 26 0 14Z"/></svg>
    <div class="pointer-events-none absolute -top-40 left-1/4 size-[30rem] rounded-full bg-[#ff4f8b]/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-40 right-0 size-[26rem] rounded-full bg-[#1fbf8f]/15 blur-3xl"></div>
    <div class="container-x relative grid gap-12 md:grid-cols-4">
        <div class="md:col-span-2">
            <x-logo size="size-16" light class="[&_.font-script]:text-4xl" />
            <p class="mt-4 max-w-md text-stone-400">{{ __('site.hero.text') }}</p>
            <div class="mt-6 flex gap-3">
                @foreach (['instagram', 'facebook', 'whatsapp', 'telegram'] as $network)
                    @if ($settings[$network])
                        @php($href = match ($network) {
                            'whatsapp' => 'https://wa.me/'.preg_replace('/\D/', '', $settings[$network]),
                            'telegram' => str_starts_with($settings[$network], 'http') ? $settings[$network] : 'https://t.me/'.ltrim($settings[$network], '@'),
                            default => $settings[$network],
                        })
                        <a href="{{ $href }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"
                           class="grid size-11 place-items-center rounded-full border border-white/10 text-stone-300 transition hover:border-gold-400 hover:bg-gold-500 hover:text-ink-950">
                            <x-social :network="$network" />
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
        <div>
            <p class="eyebrow mb-4">{{ __('site.contact.title') }}</p>
            <ul class="space-y-3 text-sm text-stone-300">
                <li class="flex gap-3"><x-icon name="phone" class="size-5 text-gold-400" /><a href="tel:{{ preg_replace('/[^\d+]/', '', $settings['phone']) }}" class="hover:text-gold-300">{{ $settings['phone'] }}</a></li>
                <li class="flex gap-3"><x-icon name="mail" class="size-5 text-gold-400" /><a href="mailto:{{ $settings['email'] }}" class="hover:text-gold-300">{{ $settings['email'] }}</a></li>
                <li class="flex gap-3"><x-icon name="pin" class="size-5 text-gold-400" />{{ $settings['address'] }}</li>
            </ul>
        </div>
        <div>
            <p class="eyebrow mb-4">Menu</p>
            <ul class="space-y-2 text-sm text-stone-300">
                <li><a href="{{ route('works.index') }}" wire:navigate class="hover:text-gold-300">{{ __('site.nav.works') }}</a></li>
                <li><a href="{{ route('videos') }}" wire:navigate class="hover:text-gold-300">{{ __('site.nav.videos') }}</a></li>
                <li><a href="{{ route('contact') }}" wire:navigate class="hover:text-gold-300">{{ __('site.nav.contact') }}</a></li>
                @guest<li><a href="{{ route('register') }}" wire:navigate class="hover:text-gold-300">{{ __('site.nav.register') }}</a></li>@endguest
            </ul>
        </div>
    </div>
    <div class="container-x relative mt-16 flex flex-col items-center justify-between gap-3 border-t border-white/8 pt-8 text-xs text-stone-500 sm:flex-row">
        <p>© {{ date('Y') }} Tokhunts Events. {{ __('site.footer.rights') }}</p>
        <p class="flex items-center gap-1.5">{{ __('site.footer.made') }} <x-icon name="heart" class="size-3.5 text-gold-500" /></p>
    </div>
</footer>
