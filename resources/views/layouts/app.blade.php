@php($settings = \App\Models\Setting::values())
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' · ' : '' }}Tokhunts Events · {{ __('site.tagline') }}</title>
    <meta name="description" content="{{ __('site.hero.text') }}">
    <meta property="og:title" content="Tokhunts Events">
    <meta property="og:description" content="{{ __('site.hero.text') }}">
    <meta name="theme-color" content="#ff4f8b">
    <meta property="og:image" content="{{ url('/logo.png') }}">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    @foreach (array_keys(config('app.locales')) as $alt)
        <link rel="alternate" hreflang="{{ $alt }}" href="{{ route('locale', $alt) }}">
    @endforeach
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=Unbounded:wght@400;500;600;700&family=Pacifico&family=Noto+Sans+Armenian:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="theme-joy min-h-screen overflow-x-clip font-sans">
    <x-site-header :settings="$settings" />

    <main>
        {{ $slot }}
    </main>

    <x-site-footer :settings="$settings" />

    @if ($settings['whatsapp'])
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings['whatsapp']) }}" target="_blank" rel="noopener"
           class="fixed right-5 bottom-5 z-40 grid size-14 place-items-center rounded-full bg-emerald-500 text-[#fff] shadow-xl shadow-emerald-500/30 transition hover:scale-110"
           aria-label="WhatsApp">
            <x-social network="whatsapp" class="size-7" />
        </a>
    @endif
</body>
</html>
