@props(['title', 'text'])
<section class="relative flex min-h-screen items-center justify-center overflow-hidden px-5 pt-28 pb-16">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=2000&q=60')] bg-cover bg-center opacity-20"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-ink-950 via-ink-950/80 to-ink-950"></div>
    <div class="pointer-events-none absolute top-20 -left-24 size-96 rounded-full bg-[#ffd6e6] blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-0 size-96 rounded-full bg-[#fff1c9] blur-3xl"></div>
    <div class="card relative w-full max-w-md p-8 sm:p-10">
        <img src="/logo.svg" alt="" class="mb-5 size-16">
        <h1 class="h-display text-3xl">{{ $title }}</h1>
        <p class="mt-2 text-sm text-stone-400">{{ $text }}</p>
        <div class="mt-8">{{ $slot }}</div>
    </div>
</section>
