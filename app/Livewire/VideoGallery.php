<?php

namespace App\Livewire;

use App\Models\WorkMedia;
use Livewire\Component;

class VideoGallery extends Component
{
    public int $limit = 12;

    public function loadMore(): void
    {
        $this->limit += 12;
    }

    public function render()
    {
        $query = WorkMedia::query()
            ->whereIn('type', ['video', 'embed'])
            ->whereHas('work', fn ($q) => $q->where('is_published', true))
            ->with('work')
            ->latest('id');

        return view('livewire.video-gallery', [
            'videos' => (clone $query)->take($this->limit)->get(),
            'total' => $query->count(),
        ]);
    }
}
