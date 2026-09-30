@php
    $fallbackHero = [
        'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=2000&q=70',
        'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=2000&q=70',
        'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&w=2000&q=70',
    ];
    $slides = $heroImages->isNotEmpty() ? $heroImages->all() : $fallbackHero;
    $heroVideo = \App\Models\Setting::get('hero_video');
    $instagram = \App\Models\Setting::get('instagram');
@endphp
<x-layouts::app>
    {{-- HERO --}}
    <section class="relative flex min-h-[100svh] items-center overflow-hidden"
             x-data="{ i: 0, slides: @js($slides), words: @js(__('site.hero.words')), w: 0 }"
             x-init="setInterval(() => i = (i + 1) % slides.length, 6000); setInterval(() => w = (w + 1) % words.length, 2600)">
        @if ($heroVideo)
            <video class="absolute inset-0 size-full object-cover" src="{{ $heroVideo }}" autoplay muted loop playsinline></video>
        @else
            <template x-for="(src, index) in slides" :key="index">
                <div class="absolute inset-0 bg-cover bg-center transition-all duration-[2000ms] ease-out"
                     :style="`background-image:url('${src}')`"
                     :class="i === index ? 'opacity-100 scale-100' : 'opacity-0 scale-110'"></div>
            </template>
        @endif
        <div class="absolute inset-0 bg-gradient-to-b from-ink-950/70 via-ink-950/55 to-ink-950"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(212,168,83,0.18),transparent_55%)]"></div>

        <div class="container-x relative z-10 pt-28 pb-20">
            <p class="eyebrow reveal">{{ __('site.hero.eyebrow') }}</p>
            <h1 class="h-display reveal mt-6 max-w-5xl text-[2.6rem] sm:text-6xl lg:text-7xl">{{ __('site.hero.title') }}</h1>
            <p class="reveal mt-8 flex flex-wrap items-baseline gap-x-3 font-display text-2xl text-stone-300 sm:text-3xl">
                {{ __('site.hero.we_organize') }}
                <span class="relative inline-grid h-[1.3em] overflow-hidden">
                    <template x-for="(word, index) in words" :key="index">
                        <span class="text-gold-gradient col-start-1 row-start-1 italic transition-all duration-700"
                              :class="w === index ? 'translate-y-0 opacity-100' : (index < w ? '-translate-y-full opacity-0' : 'translate-y-full opacity-0')"
                              x-text="word"></span>
                    </template>
                </span>
            </p>
            <p class="reveal mt-6 max-w-2xl text-lg text-stone-300">{{ __('site.hero.text') }}</p>
            <div class="reveal mt-10 flex flex-wrap gap-4">
                <a href="{{ route('contact') }}" wire:navigate class="btn-gold">{{ __('site.hero.cta') }} <x-icon name="arrow" class="size-4" /></a>
                <a href="{{ route('works.index') }}" wire:navigate class="btn-ghost">{{ __('site.hero.cta_secondary') }}</a>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 z-10 flex -translate-x-1/2 flex-col items-center gap-2 text-[10px] uppercase tracking-[0.4em] text-stone-400">
            {{ __('site.hero.scroll') }}
            <span class="h-10 w-px animate-pulse bg-gradient-to-b from-gold-400 to-transparent"></span>
        </div>
    </section>

    {{-- MARQUEE --}}
    <div class="relative overflow-hidden border-y border-white/8 bg-ink-900 py-6">
        <div class="animate-marquee flex w-max gap-12 whitespace-nowrap font-display text-3xl italic text-stone-500">
            @foreach (range(1, 2) as $_)
                @foreach (__('site.form.types') as $key => $type)
                    @continue($key === 'other')
                    <span class="flex items-center gap-12">{{ $type }} <x-icon name="sparkles" class="size-5 text-gold-500" /></span>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- ABOUT + STATS --}}
    <section id="about" class="relative py-28">
        <div class="container-x grid items-center gap-16 lg:grid-cols-2">
            <div class="reveal relative">
                <div class="grid grid-cols-2 gap-4">
                    <img src="{{ $slides[1] ?? $slides[0] }}" alt="" class="h-80 w-full rounded-[2rem] object-cover sm:h-[26rem]" loading="lazy">
                    <img src="{{ $slides[2] ?? $slides[0] }}" alt="" class="mt-16 h-80 w-full rounded-[2rem] object-cover sm:h-[26rem]" loading="lazy">
                </div>
                @isset($stats['years'])
                <div class="animate-float absolute -bottom-6 left-1/2 -translate-x-1/2 rounded-2xl border border-gold-500/30 bg-ink-900/90 px-6 py-4 text-center backdrop-blur">
                    <p class="font-display text-4xl text-gold-400">{{ $stats['years'] }}+</p>
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
                            <span class="mt-0.5 grid size-6 shrink-0 place-items-center rounded-full bg-gold-500/15 text-gold-400"><x-icon name="check" class="size-4" /></span>
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
                    <p class="font-display text-5xl text-gold-400 sm:text-6xl"><span x-text="n.toLocaleString()">{{ $value }}</span>{{ $suffix }}</p>
                    <p class="mt-2 text-xs uppercase tracking-widest text-stone-400">{{ __('site.stats.'.$key) }}</p>
                </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- SERVICES --}}
    @if ($services->isNotEmpty())
        <section id="services" class="relative scroll-mt-20 bg-ink-900/60 py-28">
            <div class="container-x">
                <x-section-heading :eyebrow="__('site.services.eyebrow')" :title="__('site.services.title')" center />
                <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <article class="reveal group card relative overflow-hidden p-8 transition duration-500 hover:-translate-y-1 hover:border-gold-500/40"
                                 style="transition-delay: {{ $loop->index * 60 }}ms">
                            <div class="absolute -top-16 -right-16 size-40 rounded-full bg-gold-500/0 blur-2xl transition duration-700 group-hover:bg-gold-500/20"></div>
                            <span class="grid size-14 place-items-center rounded-2xl border border-gold-500/30 text-gold-400 transition group-hover:bg-gold-500 group-hover:text-ink-950">
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
    <section class="relative overflow-hidden bg-ink-900/60 py-28">
        <div class="container-x">
            <x-section-heading :eyebrow="__('site.process.eyebrow')" :title="__('site.process.title')" center />
            <ol class="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach (__('site.process.steps') as [$stepTitle, $stepText])
                    <li class="reveal relative card p-8" style="transition-delay: {{ $loop->index * 100 }}ms">
                        <span class="font-display text-7xl text-gold-500/25">0{{ $loop->iteration }}</span>
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
        <section class="bg-ink-900/60 py-28" x-data="{ t: 0, n: {{ $testimonials->count() }} }" x-init="setInterval(() => t = (t + 1) % n, 7000)">
            <div class="container-x">
                <x-section-heading :eyebrow="__('site.testimonials.eyebrow')" :title="__('site.testimonials.title')" center />
                <div class="reveal relative mx-auto mt-14 grid max-w-3xl">
                    @foreach ($testimonials as $testimonial)
                        <figure class="col-start-1 row-start-1 text-center transition duration-700"
                                :class="t === {{ $loop->index }} ? 'opacity-100 translate-y-0' : 'pointer-events-none opacity-0 translate-y-4'">
                            <div class="flex justify-center gap-1 text-gold-400">
                                @for ($s = 0; $s < $testimonial->rating; $s++)<x-icon name="star" class="size-5 fill-current" />@endfor
                            </div>
                            <blockquote class="mt-6 font-display text-2xl leading-relaxed text-stone-100 italic sm:text-3xl">“{{ $testimonial->tr('text') }}”</blockquote>
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
                <a href="{{ $instagram }}" target="_blank" rel="noopener" class="reveal group relative block overflow-hidden rounded-[2.5rem] border border-white/8 p-10 sm:p-16">
                    <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-600/25 via-rose-500/15 to-amber-400/25 transition duration-700 group-hover:scale-105"></div>
                    <div class="relative flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
                        <div class="flex items-center gap-6">
                            <span class="grid size-16 place-items-center rounded-2xl bg-gradient-to-br from-fuchsia-500 via-rose-500 to-amber-400 text-white"><x-social network="instagram" class="size-8" /></span>
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
        <div class="pointer-events-none absolute top-1/2 -left-40 size-[30rem] rounded-full bg-gold-500/10 blur-3xl"></div>
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
