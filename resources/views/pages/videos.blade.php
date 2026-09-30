<x-layouts::app :title="__('site.nav.videos')">
    <x-page-hero :eyebrow="__('site.videos.eyebrow')" :title="__('site.videos.title')"
                 image="/media/works/costume-characters/1.webp">
        {{ __('site.videos.text') }}
    </x-page-hero>
    <section class="container-x pb-28">
        <livewire:video-gallery />
    </section>
</x-layouts::app>
