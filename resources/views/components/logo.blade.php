@props(['size' => 'size-11', 'light' => false])
<span {{ $attributes->class('group inline-flex items-center gap-2.5') }}>
    <img src="/logo.svg" alt="" class="{{ $size }} shrink-0 transition duration-500 group-hover:rotate-[-8deg] group-hover:scale-105" width="44" height="44">
    <span class="leading-none">
        <span @class(['block font-script text-[1.45rem] leading-[1.15] bg-gradient-to-r from-[#ff3d7f] to-[#ff7a59] bg-clip-text text-transparent', '!from-[#ff9cc0] !to-[#ffc93c]' => $light])>Tokhunts</span>
        <span @class(['block pl-0.5 font-display text-[9px] font-semibold tracking-[0.45em] uppercase', 'text-[#0e9f78]' => ! $light, 'text-[#7ee2bd]' => $light])>Events</span>
    </span>
</span>
