<x-layouts::app :title="__('site.nav.videos')">
    <x-page-hero :eyebrow="__('site.videos.eyebrow')" :title="__('site.videos.title')"
                 image="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=2000&q=60">
        {{ __('site.videos.text') }}
    </x-page-hero>
    <section class="container-x pb-28">
        <livewire:video-gallery />
    </section>
</x-layouts::app>
