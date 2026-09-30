@props(['settings'])
@php
    $links = [
        'home' => route('home'),
        'services' => route('home').'#services',
        'works' => route('works.index'),
        'videos' => route('videos'),
        'contact' => route('contact'),
    ];
@endphp
<header x-data="{ open: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 40"
        @scroll.window="scrolled = window.scrollY > 40"
        :class="scrolled || open ? 'bg-ink-950/85 backdrop-blur-xl border-white/8' : 'bg-transparent border-transparent'"
        class="fixed inset-x-0 top-0 z-50 border-b transition-colors duration-500">
    <div class="container-x flex h-20 items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="group flex items-center gap-3" wire:navigate>
            <span class="grid size-10 place-items-center rounded-full border border-gold-500/50 font-display text-xl text-gold-400 transition group-hover:bg-gold-500 group-hover:text-ink-950">T</span>
            <span class="leading-tight">
                <span class="block font-display text-xl tracking-wide text-stone-50">Tokhunts</span>
                <span class="block text-[10px] uppercase tracking-[0.35em] text-gold-400">Events</span>
            </span>
        </a>

        <nav class="hidden items-center gap-8 lg:flex">
            @foreach ($links as $key => $url)
                <a href="{{ $url }}" @if($key !== 'services') wire:navigate @endif
                   class="relative text-sm text-stone-300 transition hover:text-gold-300 after:absolute after:-bottom-1 after:left-0 after:h-px after:w-0 after:bg-gold-400 after:transition-all hover:after:w-full">
                    {{ __('site.nav.'.$key) }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-3">
            <x-locale-switcher />

            @auth
                <div x-data="{ menu: false }" class="relative hidden sm:block">
                    <button @click="menu = !menu" @click.outside="menu = false" class="grid size-10 place-items-center rounded-full border border-white/10 text-stone-300 hover:border-gold-400 hover:text-gold-300" aria-label="{{ auth()->user()->name }}">
                        <x-icon name="user" />
                    </button>
                    <div x-cloak x-show="menu" x-transition class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-white/10 bg-ink-900 py-2 shadow-2xl">
                        <p class="truncate px-4 py-2 text-xs text-stone-500">{{ auth()->user()->email }}</p>
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gold-300 hover:bg-white/5">{{ __('site.nav.admin') }}</a>
                        @endif
                        <a href="{{ route('account') }}" wire:navigate class="block px-4 py-2 text-sm hover:bg-white/5">{{ __('site.nav.account') }}</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="w-full px-4 py-2 text-left text-sm text-stone-400 hover:bg-white/5">{{ __('site.nav.logout') }}</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" wire:navigate class="hidden text-sm text-stone-300 hover:text-gold-300 sm:block">{{ __('site.nav.login') }}</a>
            @endauth

            <a href="{{ route('contact') }}" wire:navigate class="btn-gold hidden !px-5 !py-2.5 md:inline-flex">{{ __('site.nav.book') }}</a>

            <button @click="open = !open" class="grid size-10 place-items-center rounded-full border border-white/10 lg:hidden" aria-label="Menu">
                <x-icon name="menu" x-show="!open" />
                <x-icon name="x" x-cloak x-show="open" />
            </button>
        </div>
    </div>

    <div x-cloak x-show="open" x-collapse class="border-t border-white/8 lg:hidden">
        <nav class="container-x flex flex-col gap-1 py-4">
            @foreach ($links as $key => $url)
                <a href="{{ $url }}" @click="open = false" class="rounded-xl px-3 py-3 font-display text-2xl text-stone-100 hover:bg-white/5">{{ __('site.nav.'.$key) }}</a>
            @endforeach
            <div class="mt-3 flex flex-wrap gap-3 border-t border-white/8 pt-4">
                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="btn-ghost">{{ __('site.nav.admin') }}</a>
                    @endif
                    <a href="{{ route('account') }}" class="btn-ghost">{{ __('site.nav.account') }}</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn-ghost">{{ __('site.nav.logout') }}</button></form>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost">{{ __('site.nav.login') }}</a>
                    <a href="{{ route('register') }}" class="btn-ghost">{{ __('site.nav.register') }}</a>
                @endauth
                <a href="{{ route('contact') }}" class="btn-gold">{{ __('site.nav.book') }}</a>
            </div>
        </nav>
    </div>
</header>
