<div x-data="{ open: null }" @keydown.escape.window="open = null">
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($videos as $video)
            <button type="button" wire:key="video-{{ $video->id }}"
                    @click="open = @js(['type' => $video->type, 'src' => $video->type === 'embed' ? $video->embedUrl() : $video->src()])"
                    class="theme-plum reveal group relative aspect-video overflow-hidden rounded-[1.75rem] bg-ink-800 text-left shadow-[0_25px_50px_-25px_rgb(43_21_55/0.5)]">
                @if ($video->type === 'video')
                    <video src="{{ $video->src() }}#t=1" muted playsinline preload="metadata" class="absolute inset-0 size-full object-cover transition duration-1000 group-hover:scale-105"
                           @mouseenter="$el.play()" @mouseleave="$el.pause()"></video>
                @elseif ($video->thumbnail())
                    <img src="{{ $video->thumbnail() }}" alt="" loading="lazy" class="absolute inset-0 size-full object-cover transition duration-1000 group-hover:scale-105">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-ink-950/90 via-transparent to-transparent"></div>
                <span class="absolute top-1/2 left-1/2 grid size-16 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-gradient-to-br from-[#ff4f8b] to-[#ffb23c] text-[#fff] shadow-2xl shadow-gold-500/40 transition group-hover:scale-110">
                    <x-icon name="play" class="ml-1 size-7 fill-current" />
                </span>
                <div class="absolute inset-x-0 bottom-0 p-5">
                    <p class="font-display text-lg font-semibold text-stone-50">{{ $video->caption ?: $video->work->tr('title') }}</p>
                </div>
            </button>
        @empty
            <p class="col-span-full py-12 text-center text-stone-500">{{ __('site.videos.empty') }}</p>
        @endforelse
    </div>

    @if ($total > $videos->count() && $limit > 3)
        <div class="mt-12 text-center">
            <button wire:click="loadMore" class="btn-ghost">{{ __('site.works.more') }}</button>
        </div>
    @endif

    <template x-teleport="body">
        <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-[100] grid place-items-center bg-ink-950/95 p-4 backdrop-blur" @click.self="open = null">
            <button @click="open = null" class="absolute top-5 right-5 grid size-12 place-items-center rounded-full border border-white/15 text-stone-200 hover:border-gold-400" aria-label="Close">
                <x-icon name="x" />
            </button>
            <div class="aspect-video w-full max-w-5xl overflow-hidden rounded-3xl bg-black">
                <template x-if="open && open.type === 'video'">
                    <video :src="open.src" controls autoplay playsinline class="size-full"></video>
                </template>
                <template x-if="open && open.type === 'embed'">
                    <iframe :src="open.src + '&autoplay=1'" class="size-full" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                </template>
            </div>
        </div>
    </template>
</div>
