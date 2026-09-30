@php
    $images = $work->media->where('type', 'image')->values();
    $videos = $work->media->where('type', '!=', 'image')->values();
    $cover = $work->cover();
@endphp
<x-layouts::app :title="$work->tr('title')">
    <section class="relative flex min-h-[75vh] items-end overflow-hidden pt-32 pb-16">
        @if ($cover?->thumbnail())
            <div class="absolute inset-0 scale-105 bg-cover bg-center" style="background-image:url('{{ $cover->thumbnail() }}')"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-b from-ink-950/40 via-ink-950/60 to-ink-950"></div>
        <div class="container-x relative">
            <a href="{{ route('works.index') }}" wire:navigate class="inline-flex items-center gap-2 text-sm text-stone-300 hover:text-gold-300"><x-icon name="arrow" class="size-4 rotate-180" /> {{ __('site.works.back') }}</a>
            @if ($work->category)<p class="eyebrow mt-8">{{ $work->category->tr('name') }}</p>@endif
            <h1 class="h-display mt-4 max-w-4xl text-5xl sm:text-7xl">{{ $work->tr('title') }}</h1>
            <div class="mt-6 flex flex-wrap gap-6 text-sm text-stone-300">
                @if ($work->event_date)<span class="flex items-center gap-2"><x-icon name="calendar" class="size-4 text-gold-400" />{{ $work->event_date->translatedFormat('d F Y') }}</span>@endif
                @if ($work->location)<span class="flex items-center gap-2"><x-icon name="pin" class="size-4 text-gold-400" />{{ $work->location }}</span>@endif
                <span class="flex items-center gap-2"><x-icon name="eye" class="size-4 text-gold-400" />{{ $work->views }} {{ __('site.works.views') }}</span>
            </div>
        </div>
    </section>

    @if ($work->tr('description'))
        <section class="container-x py-12">
            <div class="reveal max-w-3xl text-lg leading-relaxed whitespace-pre-line text-stone-300">{{ $work->tr('description') }}</div>
        </section>
    @endif

    <section class="container-x pb-20"
             x-data="{ lightbox: null, images: @js($images->map(fn ($m) => $m->src())->all()) }"
             @keydown.escape.window="lightbox = null"
             @keydown.arrow-right.window="if (lightbox !== null) lightbox = (lightbox + 1) % images.length"
             @keydown.arrow-left.window="if (lightbox !== null) lightbox = (lightbox - 1 + images.length) % images.length">
        @if ($videos->isNotEmpty())
            <div class="mb-10 grid gap-6 md:grid-cols-2">
                @foreach ($videos as $video)
                    <div class="reveal aspect-video overflow-hidden rounded-3xl bg-black">
                        @if ($video->type === 'embed' && $video->embedUrl())
                            <iframe src="{{ $video->embedUrl() }}" class="size-full" loading="lazy" allow="fullscreen; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <video src="{{ $video->src() }}" controls playsinline preload="metadata" class="size-full"></video>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <div class="columns-1 gap-5 sm:columns-2 lg:columns-3">
            @foreach ($images as $image)
                <button type="button" @click="lightbox = {{ $loop->index }}" class="reveal group mb-5 block w-full overflow-hidden rounded-3xl">
                    <img src="{{ $image->src() }}" alt="{{ $image->caption ?? $work->tr('title') }}" loading="lazy" class="w-full transition duration-1000 group-hover:scale-105">
                </button>
            @endforeach
        </div>

        <template x-teleport="body">
            <div x-cloak x-show="lightbox !== null" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center bg-ink-950/95 p-4 backdrop-blur" @click.self="lightbox = null">
                <img :src="images[lightbox]" class="max-h-[88vh] max-w-full rounded-2xl object-contain shadow-2xl" alt="">
                <button @click="lightbox = null" class="absolute top-5 right-5 grid size-12 place-items-center rounded-full border border-white/15" aria-label="Close"><x-icon name="x" /></button>
                <button @click="lightbox = (lightbox - 1 + images.length) % images.length" class="absolute left-4 grid size-12 place-items-center rounded-full border border-white/15 bg-ink-950/60" aria-label="Previous"><x-icon name="arrow" class="size-5 rotate-180" /></button>
                <button @click="lightbox = (lightbox + 1) % images.length" class="absolute right-4 grid size-12 place-items-center rounded-full border border-white/15 bg-ink-950/60" aria-label="Next"><x-icon name="arrow" class="size-5" /></button>
                <p class="absolute bottom-6 text-sm text-stone-400" x-text="`${lightbox + 1} / ${images.length}`"></p>
            </div>
        </template>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-white/8 bg-ink-900/60 py-20">
            <div class="container-x">
                <h2 class="h-display text-4xl">{{ __('site.works.related') }}</h2>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($related as $item)
                        <a href="{{ route('works.show', $item) }}" wire:navigate class="group relative block aspect-[4/5] overflow-hidden rounded-3xl bg-ink-800">
                            @if ($item->cover()?->thumbnail())
                                <img src="{{ $item->cover()->thumbnail() }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover transition duration-1000 group-hover:scale-110">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-ink-950 to-transparent"></div>
                            <p class="absolute bottom-6 left-6 font-display text-2xl text-stone-50">{{ $item->tr('title') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="container-x py-20 text-center">
        <h2 class="h-display text-4xl sm:text-5xl">{{ __('site.form.title') }}</h2>
        <a href="{{ route('contact') }}" wire:navigate class="btn-gold mt-8">{{ __('site.hero.cta') }} <x-icon name="arrow" class="size-4" /></a>
    </section>
</x-layouts::app>
