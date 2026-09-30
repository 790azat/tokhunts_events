@props(['eyebrow', 'title', 'center' => false])
<div {{ $attributes->class(['reveal max-w-3xl', 'mx-auto text-center' => $center]) }}>
    <p class="eyebrow">{{ $eyebrow }}</p>
    <h2 class="h-display mt-4 text-3xl sm:text-4xl lg:text-5xl">{{ $title }}</h2>
    @isset($slot)<div class="mt-5 text-lg text-stone-400">{{ $slot }}</div>@endisset
</div>
