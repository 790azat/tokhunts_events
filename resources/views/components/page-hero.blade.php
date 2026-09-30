@props(['eyebrow', 'title', 'image' => 'https://images.unsplash.com/photo-1478146896981-b80fe463b330?auto=format&fit=crop&w=2000&q=60'])
<section class="relative overflow-hidden pt-40 pb-20">
    <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image:url('{{ $image }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-ink-950/60 to-ink-950"></div>
    <div class="pointer-events-none absolute -top-24 -right-24 size-96 rounded-full bg-[#ffd6e6] blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -left-24 size-96 rounded-full bg-[#d9f7ea] blur-3xl"></div>
    <x-balloon color="#ffc93c" class="animate-rise absolute top-28 right-[8%] hidden w-12 md:block" />
    <x-balloon color="#ff4f8b" class="animate-rise absolute top-40 right-[16%] hidden w-9 [animation-delay:-5s] md:block" />
    <div class="container-x relative">
        <p class="eyebrow reveal">{{ $eyebrow }}</p>
        <h1 class="h-display reveal mt-4 text-4xl sm:text-6xl">{{ $title }}</h1>
        @if ($slot->isNotEmpty())<div class="reveal mt-5 max-w-2xl text-lg text-stone-400">{{ $slot }}</div>@endif
    </div>
</section>
