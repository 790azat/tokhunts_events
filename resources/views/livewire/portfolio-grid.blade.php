<div>
    @if ($categories->count() > 1)
        <div class="reveal mb-10 flex flex-wrap gap-2">
            <button wire:click="filter('')" @class(['rounded-full border px-5 py-2 text-sm transition', 'border-gold-500 bg-gold-500 text-ink-950' => $category === '', 'border-white/10 text-stone-300 hover:border-gold-400' => $category !== ''])>{{ __('site.works.all') }}</button>
            @foreach ($categories as $cat)
                <button wire:click="filter('{{ $cat->slug }}')" @class(['rounded-full border px-5 py-2 text-sm transition', 'border-gold-500 bg-gold-500 text-ink-950' => $category === $cat->slug, 'border-white/10 text-stone-300 hover:border-gold-400' => $category !== $cat->slug])>{{ $cat->tr('name') }}</button>
            @endforeach
        </div>
    @endif

    <div wire:loading.class="opacity-50" class="grid gap-6 transition sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($works as $work)
            @php($cover = $work->cover())
            <a href="{{ route('works.show', $work) }}" wire:navigate wire:key="work-{{ $work->id }}"
               class="theme-plum reveal group relative block overflow-hidden rounded-[2rem] bg-ink-800 shadow-[0_25px_50px_-25px_rgb(43_21_55/0.5)] transition duration-500 hover:-translate-y-1.5 hover:rotate-[-0.6deg]">
                <div class="relative aspect-[4/5] w-full overflow-hidden">
                    @if ($cover && $cover->type === 'video')
                        <video src="{{ $cover->src() }}#t=1" muted playsinline preload="metadata" class="absolute inset-0 size-full object-cover transition duration-[1500ms] group-hover:scale-110"></video>
                    @elseif ($cover?->thumbnail())
                        <img src="{{ $cover->thumbnail() }}" alt="{{ $work->tr('title') }}" loading="lazy" class="absolute inset-0 size-full object-cover transition duration-[1500ms] group-hover:scale-110">
                    @else
                        <div class="absolute inset-0 grid place-items-center bg-gradient-to-br from-ink-700 to-ink-900 text-gold-500/40"><x-icon name="sparkles" class="size-16" /></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-950/90 via-ink-950/10 to-transparent opacity-90 transition group-hover:opacity-100"></div>
                    <div class="absolute inset-x-0 bottom-0 p-7">
                        @if ($work->category)
                            <p class="eyebrow">{{ $work->category->tr('name') }}</p>
                        @endif
                        <h3 class="mt-2 font-display text-2xl font-semibold leading-tight text-stone-50">{{ $work->tr('title') }}</h3>
                        <div class="mt-3 flex items-center gap-4 text-xs text-stone-400">
                            @if ($work->location)<span class="flex items-center gap-1"><x-icon name="pin" class="size-3.5" />{{ $work->location }}</span>@endif
                            <span class="flex items-center gap-1"><x-icon name="photo" class="size-3.5" />{{ $work->media->where('type', 'image')->count() }}</span>
                            @if ($work->media->where('type', '!=', 'image')->count())
                                <span class="flex items-center gap-1"><x-icon name="video" class="size-3.5" />{{ $work->media->where('type', '!=', 'image')->count() }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="absolute top-6 right-6 grid size-12 translate-y-2 place-items-center rounded-full bg-gold-500 text-ink-950 opacity-0 transition duration-500 group-hover:translate-y-0 group-hover:opacity-100">
                        <x-icon name="arrow" class="size-5 -rotate-45" />
                    </span>
                </div>
            </a>
        @empty
            <p class="col-span-full py-16 text-center text-stone-500">{{ __('site.works.empty') }}</p>
        @endforelse
    </div>

    @if ($total > $works->count())
        <div class="mt-12 text-center">
            <button wire:click="loadMore" wire:loading.attr="disabled" class="btn-ghost">
                <span wire:loading.remove wire:target="loadMore">{{ __('site.works.more') }}</span>
                <span wire:loading wire:target="loadMore">…</span>
            </button>
        </div>
    @endif
</div>
