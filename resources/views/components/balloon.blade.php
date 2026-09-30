@props(['color' => '#ff4f8b'])
<svg {{ $attributes->merge(['viewBox' => '0 0 60 120', 'aria-hidden' => 'true']) }} xmlns="http://www.w3.org/2000/svg">
    <path d="M30 66c-2 10 6 16 0 26s4 18 0 28" fill="none" stroke="#c9b6d3" stroke-width="1.5"/>
    <ellipse cx="30" cy="32" rx="25" ry="30" fill="{{ $color }}"/>
    <ellipse cx="21" cy="20" rx="6" ry="10" fill="#fff" opacity=".35" transform="rotate(-20 21 20)"/>
    <path d="M26 61h8l-4 7z" fill="{{ $color }}"/>
</svg>
