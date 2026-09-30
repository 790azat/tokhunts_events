@props(['eyebrow', 'title', 'image' => 'https://images.unsplash.com/photo-1478146896981-b80fe463b330?auto=format&fit=crop&w=2000&q=60'])
<section class="relative overflow-hidden pt-40 pb-20">
    <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image:url('{{ $image }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-ink-950/60 to-ink-950"></div>
    <div class="container-x relative">
        <p class="eyebrow reveal">{{ $eyebrow }}</p>
        <h1 class="h-display reveal mt-4 text-5xl sm:text-7xl">{{ $title }}</h1>
        @if ($slot->isNotEmpty())<div class="reveal mt-5 max-w-2xl text-lg text-stone-400">{{ $slot }}</div>@endif
    </div>
</section>
