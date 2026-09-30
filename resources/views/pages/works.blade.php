<x-layouts::app :title="__('site.works.title')">
    <x-page-hero :eyebrow="__('site.works.eyebrow')" :title="__('site.works.title')" />
    <section class="container-x pb-28">
        <livewire:portfolio-grid :per-page="9" />
    </section>
</x-layouts::app>
