<div class="flex items-center rounded-full border border-white/10 p-1 text-xs">
    @foreach (config('app.locales') as $code => $label)
        <a href="{{ route('locale', $code) }}"
           @class([
               'rounded-full px-2.5 py-1 font-semibold transition',
               'bg-gold-500 text-ink-950' => app()->getLocale() === $code,
               'text-stone-400 hover:text-gold-300' => app()->getLocale() !== $code,
           ])
           hreflang="{{ $code }}" lang="{{ $code }}">{{ $label }}</a>
    @endforeach
</div>
