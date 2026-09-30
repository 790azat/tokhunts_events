@php
    $fallbackHero = [
        '/media/works/pearl-birthday/1.webp',
        '/media/works/white-wedding-decor/1.webp',
        '/media/works/blue-arch-kids-birthday/1.webp',
    ];
    $slides = $heroImages->isNotEmpty() ? $heroImages->all() : $fallbackHero;
    $heroVideo = \App\Models\Setting::get('hero_video');
    $instagram = \App\Models\Setting::get('instagram');
@endphp
<x-layouts::app>
    {{-- HERO --}}
    <section class="relative flex min-h-[100svh] items-center overflow-hidden bg-[#fffaf3]"
             x-data="{ i: 0, slides: @js($slides), words: @js(__('site.hero.words')), w: 0 }"
             x-init="setInterval(() => i = (i + 1) % slides.length, 5000); setInterval(() => w = (w + 1) % words.length, 2400)">
        <div class="pointer-events-none absolute -top-32 -left-32 size-[34rem] rounded-full bg-[#ffd6e6] blur-3xl"></div>
        <div class="pointer-events-none absolute top-1/3 -right-40 size-[30rem] rounded-full bg-[#d9f7ea] blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-40 left-1/3 size-[28rem] rounded-full bg-[#fff1c9] blur-3xl"></div>
        <canvas data-confetti class="pointer-events-none absolute inset-0 size-full"></canvas>

        <div class="container-x relative z-10 grid items-center gap-14 pt-32 pb-24 lg:grid-cols-[1.1fr_1fr]">
            <div class="min-w-0">
                <p class="reveal inline-flex items-center gap-2 rounded-full bg-[#fff] px-4 py-2 text-xs font-bold tracking-[0.2em] text-[#f23d7c] uppercase shadow-[0_8px_24px_-12px_rgb(255_79_139/0.6)]">
                    <x-icon name="sparkles" class="size-4 text-[#ffb23c]" /> {{ __('site.hero.eyebrow') }}
                </p>
                <h1 class="h-display reveal mt-7 max-w-3xl text-[2rem] break-words sm:text-5xl lg:text-[3.6rem]">{{ __('site.hero.title') }}</h1>
                <p class="reveal mt-7 flex flex-wrap items-center gap-x-3 text-xl font-bold text-stone-300 sm:text-2xl">
                    {{ __('site.hero.we_organize') }}
                    <span class="relative inline-grid max-w-full">
                        <template x-for="(word, index) in words" :key="index">
                            <span class="col-start-1 row-start-1 py-1 transition duration-700"
                                  :class="w === index ? 'translate-y-0 scale-100 rotate-0 opacity-100' : 'pointer-events-none translate-y-3 scale-75 -rotate-3 opacity-0'">
                                <span class="font-script text-3xl leading-normal font-normal sm:text-4xl" :class="'text-joy-' + (index % 5)" x-text="word"></span>
                            </span>
                        </template>
                    </span>
                </p>
                <p class="reveal mt-5 max-w-xl text-lg text-stone-300">{{ __('site.hero.text') }}</p>
                <div class="reveal mt-9 flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" wire:navigate class="btn-gold !px-7 !py-3.5 !text-base" data-confetti-burst>{{ __('site.hero.cta') }} <x-icon name="arrow" class="size-4" /></a>
                    <a href="{{ route('works.index') }}" wire:navigate class="btn-ghost !px-7 !py-3.5 !text-base">{{ __('site.hero.cta_secondary') }}</a>
                </div>
            </div>

            <div class="reveal relative mx-auto aspect-square w-full max-w-[34rem]">
                <div class="absolute inset-[6%] overflow-hidden border-[10px] border-[#fff] bg-[#ffe4ee] shadow-[0_40px_80px_-30px_rgb(255_79_139/0.55)]"
                     style="border-radius: 58% 42% 55% 45% / 47% 58% 42% 53%">
                    @if ($heroVideo)
                        <video class="size-full object-cover" src="{{ $heroVideo }}" autoplay muted loop playsinline></video>
                    @else
                        <template x-for="(src, index) in slides" :key="index">
                            <div class="absolute inset-0 bg-cover bg-center transition-all duration-[1600ms] ease-out"
                                 :style="`background-image:url('${src}')`"
                                 :class="i === index ? 'opacity-100 scale-100' : 'opacity-0 scale-110'"></div>
                        </template>
                    @endif
                </div>
                <img src="/logo.svg" alt="" class="animate-float absolute -bottom-2 -left-2 size-28 drop-shadow-xl sm:size-36">
                <x-balloon color="#ff4f8b" class="animate-rise absolute -top-6 right-6 w-14 sm:w-16" />
                <x-balloon color="#ffc93c" class="animate-rise absolute top-10 -right-3 w-10 [animation-delay:-4s] sm:w-12" />
                <x-balloon color="#4cc9f0" class="animate-rise absolute top-2 left-4 w-9 [animation-delay:-8s] sm:w-11" />
                <span class="animate-wiggle absolute right-2 bottom-10 grid size-16 place-items-center rounded-full bg-[#1fbf8f] text-3xl shadow-lg sm:size-20" aria-hidden="true">🎉</span>
                <span class="animate-spin-slow absolute top-1/2 -left-4 text-4xl text-[#ffc93c]" aria-hidden="true">✦</span>
                <span class="animate-spin-slow absolute top-4 left-1/2 text-2xl text-[#9b5de5]" aria-hidden="true">✦</span>
            </div>
        </div>

        <svg class="absolute inset-x-0 bottom-0 h-12 w-full text-[#fff]" viewBox="0 0 1440 48" preserveAspectRatio="none" aria-hidden="true"><path fill="currentColor" d="M0 48h1440V22c-160 20-320 26-480 12S640 0 480 6 160 36 0 20Z"/></svg>
    </section>

    {{-- MARQUEE --}}
    <div class="relative z-10 -my-3 -rotate-1 overflow-hidden bg-gradient-to-r from-[#ff4f8b] via-[#ff8a3d] to-[#ffc93c] py-5 shadow-[0_20px_40px_-25px_rgb(255_79_139/0.8)]">
        <div class="animate-marquee flex w-max gap-10 whitespace-nowrap font-display text-2xl font-semibold text-[#fff] sm:text-3xl">
            @foreach (range(1, 2) as $_)
                @foreach (__('site.form.types') as $key => $type)
                    @continue($key === 'other')
                    <span class="flex items-center gap-10">{{ $type }} <span class="text-[#2b1537]/40">✦</span></span>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- ABOUT + STATS --}}
    <section id="about" class="relative py-28">
        <div class="container-x grid items-center gap-16 lg:grid-cols-2">
            <div class="reveal relative">
                <div class="grid grid-cols-2 gap-4">
                    <img src="{{ $slides[1] ?? $slides[0] }}" alt="" class="h-80 w-full rounded-[2rem] border-8 border-[#fff] object-cover shadow-xl sm:h-[26rem] -rotate-2" loading="lazy">
                    <img src="{{ $slides[2] ?? $slides[0] }}" alt="" class="mt-16 h-80 w-full rounded-[2rem] border-8 border-[#fff] object-cover shadow-xl sm:h-[26rem] rotate-2" loading="lazy">
                </div>
                @isset($stats['years'])
                <div class="animate-float absolute -bottom-6 left-1/2 -translate-x-1/2 rounded-3xl bg-[#fff] px-6 py-4 text-center shadow-[0_20px_40px_-20px_rgb(255_79_139/0.6)]">
                    <p class="font-display text-4xl font-bold text-rainbow">{{ $stats['years'] }}+</p>
                    <p class="text-xs uppercase tracking-widest text-stone-400">{{ __('site.stats.years') }}</p>
                </div>
                @endisset
            </div>
            <div>
                <x-section-heading :eyebrow="__('site.about.eyebrow')" :title="__('site.about.title')">
                    {{ __('site.about.text') }}
                </x-section-heading>
                <ul class="reveal mt-8 space-y-4">
                    @foreach (__('site.about.points') as $point)
                        <li class="flex items-start gap-3 text-stone-300">
                            <span class="joy-{{ $loop->index % 5 }} mt-0.5 grid size-7 shrink-0 place-items-center rounded-full"><x-icon name="check" class="size-4" /></span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        @if ($stats)
        <div @class(['container-x mt-24 grid grid-cols-2 gap-6', 'lg:grid-cols-3' => count($stats) === 3, 'lg:grid-cols-2' => count($stats) < 3])>
            @foreach ($stats as $key => $value)
                @php($suffix = '+')
                <div class="reveal card p-8 text-center"
                     x-data="{ n: 0 }"
                     x-intersect.once="let start = null; const step = (t) => { start ??= t; const p = Math.min((t - start) / 1800, 1); n = Math.floor({{ $value }} * (1 - Math.pow(1 - p, 3))); if (p < 1) requestAnimationFrame(step) }; requestAnimationFrame(step)">
                    <p class="text-joy-{{ $loop->index % 5 }} font-display text-5xl font-bold sm:text-6xl"><span x-text="n.toLocaleString()">{{ $value }}</span>{{ $suffix }}</p>
                    <p class="mt-2 text-xs uppercase tracking-widest text-stone-400">{{ __('site.stats.'.$key) }}</p>
                </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- SERVICES --}}
    @if ($services->isNotEmpty())
        <section id="services" class="bg-confetti relative scroll-mt-20 py-28">
            <div class="container-x">
                <x-section-heading :eyebrow="__('site.services.eyebrow')" :title="__('site.services.title')" center />
                <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <article class="reveal group card relative overflow-hidden p-8 transition duration-500 hover:-translate-y-2 hover:border-gold-500/40"
                                 style="transition-delay: {{ $loop->index * 60 }}ms">
                            <div class="joy-{{ $loop->index % 5 }} absolute -top-16 -right-16 size-40 rounded-full opacity-0 blur-2xl transition duration-700 group-hover:opacity-100"></div>
                            <span class="joy-{{ $loop->index % 5 }} relative grid size-16 place-items-center rounded-2xl transition duration-500 group-hover:-rotate-6 group-hover:scale-110">
                                <x-icon :name="$service->icon" class="size-7" />
                            </span>
                            <h3 class="mt-6 font-display text-2xl text-stone-50">{{ $service->tr('title') }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-stone-400">{{ $service->tr('description') }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- PORTFOLIO --}}
    <section class="py-28">
        <div class="container-x">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <x-section-heading :eyebrow="__('site.works.eyebrow')" :title="__('site.works.title')" />
                <a href="{{ route('works.index') }}" wire:navigate class="btn-ghost reveal">{{ __('site.works.view') }} <x-icon name="arrow" class="size-4" /></a>
            </div>
            <div class="mt-14">
                <livewire:portfolio-grid :per-page="6" />
            </div>
        </div>
    </section>

    {{-- PROCESS --}}
    <section class="bg-confetti relative overflow-hidden py-28">
        <div class="container-x">
            <x-section-heading :eyebrow="__('site.process.eyebrow')" :title="__('site.process.title')" center />
            <ol class="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach (__('site.process.steps') as [$stepTitle, $stepText])
                    <li class="reveal relative card p-8" style="transition-delay: {{ $loop->index * 100 }}ms">
                        <span class="text-joy-{{ $loop->index % 5 }} font-display text-6xl font-bold opacity-80">0{{ $loop->iteration }}</span>
                        <h3 class="mt-2 font-display text-2xl text-stone-50">{{ $stepTitle }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-stone-400">{{ $stepText }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- VIDEOS --}}
    <section class="py-28">
        <div class="container-x">
            <div class="flex flex-wrap items-end justify-between gap-6">
                <x-section-heading :eyebrow="__('site.videos.eyebrow')" :title="__('site.videos.title')">{{ __('site.videos.text') }}</x-section-heading>
                <a href="{{ route('videos') }}" wire:navigate class="btn-ghost reveal">{{ __('site.nav.videos') }} <x-icon name="arrow" class="size-4" /></a>
            </div>
            <div class="mt-14">
                <livewire:video-gallery :limit="3" />
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-confetti py-28" x-data="{ t: 0, n: {{ $testimonials->count() }} }" x-init="setInterval(() => t = (t + 1) % n, 7000)">
            <div class="container-x">
                <x-section-heading :eyebrow="__('site.testimonials.eyebrow')" :title="__('site.testimonials.title')" center />
                <div class="reveal relative mx-auto mt-14 grid max-w-3xl">
                    @foreach ($testimonials as $testimonial)
                        <figure class="col-start-1 row-start-1 text-center transition duration-700"
                                :class="t === {{ $loop->index }} ? 'opacity-100 translate-y-0' : 'pointer-events-none opacity-0 translate-y-4'">
                            <div class="flex justify-center gap-1 text-gold-400">
                                @for ($s = 0; $s < $testimonial->rating; $s++)<x-icon name="star" class="size-5 fill-current" />@endfor
                            </div>
                            <blockquote class="mt-6 font-display text-xl leading-relaxed text-stone-100 sm:text-2xl">“{{ $testimonial->tr('text') }}”</blockquote>
                            <figcaption class="mt-6 text-sm text-stone-400"><span class="font-semibold text-gold-300">{{ $testimonial->author }}</span>@if ($testimonial->event) · {{ $testimonial->event }}@endif</figcaption>
                        </figure>
                    @endforeach
                </div>
                <div class="mt-10 flex justify-center gap-2">
                    @foreach ($testimonials as $testimonial)
                        <button @click="t = {{ $loop->index }}" class="h-1.5 rounded-full transition-all" :class="t === {{ $loop->index }} ? 'w-8 bg-gold-400' : 'w-3 bg-white/20'" aria-label="{{ $loop->iteration }}"></button>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- INSTAGRAM --}}
    @if ($instagram)
        <section class="py-20">
            <div class="container-x">
                <a href="{{ $instagram }}" target="_blank" rel="noopener" class="reveal group relative block overflow-hidden rounded-[2.5rem] p-10 shadow-[0_30px_60px_-30px_rgb(255_79_139/0.6)] sm:p-16">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#ffd6e6] via-[#fff1c9] to-[#d9f7ea] transition duration-700 group-hover:scale-105"></div>
                    <div class="relative flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
                        <div class="flex items-center gap-6">
                            <span class="grid size-16 place-items-center rounded-2xl bg-gradient-to-br from-fuchsia-500 via-rose-500 to-amber-400 text-[#fff]"><x-social network="instagram" class="size-8" /></span>
                            <div>
                                <p class="font-display text-3xl text-stone-50 sm:text-4xl">{{ __('site.instagram.title') }}</p>
                                <p class="mt-1 text-stone-300">@ {{ trim(parse_url($instagram, PHP_URL_PATH) ?? '', '/') }} · {{ __('site.instagram.text') }}</p>
                            </div>
                        </div>
                        <span class="btn-gold">{{ __('site.instagram.cta') }} <x-icon name="arrow" class="size-4" /></span>
                    </div>
                </a>
            </div>
        </section>
    @endif

    {{-- REQUEST FORM --}}
    <section id="request" class="relative overflow-hidden py-28">
        <div class="pointer-events-none absolute top-1/2 -left-40 size-[30rem] rounded-full bg-[#ffd6e6] blur-3xl"></div>
        <div class="pointer-events-none absolute -right-40 bottom-0 size-[26rem] rounded-full bg-[#daf3fc] blur-3xl"></div>
        <div class="container-x relative grid gap-14 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <x-section-heading :eyebrow="__('site.form.eyebrow')" :title="__('site.form.title')">{{ __('site.form.text') }}</x-section-heading>
            </div>
            <div class="lg:col-span-3">
                <livewire:inquiry-form />
            </div>
        </div>
    </section>
</x-layouts::app>
