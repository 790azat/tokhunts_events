<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Work;
use App\Models\WorkMedia;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts::admin')]
class WorkForm extends Component
{
    use WithFileUploads;

    public ?Work $work = null;

    public array $title = ['hy' => '', 'ru' => '', 'en' => ''];

    public array $description = ['hy' => '', 'ru' => '', 'en' => ''];

    public ?int $category_id = null;

    public string $location = '';

    public string $event_date = '';

    public bool $is_featured = false;

    public bool $is_published = true;

    /** @var array<\Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $photos = [];

    /** @var array<\Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $videos = [];

    public string $link = '';

    public array $captions = [];

    public function mount(?Work $work = null): void
    {
        if ($work?->exists) {
            $this->work = $work;
            $this->title = array_merge($this->title, (array) $work->title);
            $this->description = array_merge($this->description, (array) $work->description);
            $this->category_id = $work->category_id;
            $this->location = (string) $work->location;
            $this->event_date = (string) $work->event_date?->toDateString();
            $this->is_featured = $work->is_featured;
            $this->is_published = $work->is_published;
            $this->captions = $work->media->pluck('caption', 'id')->map(fn ($c) => (string) $c)->all();
        }
    }

    protected function rules(): array
    {
        return [
            'title.ru' => 'required_without_all:title.hy,title.en|nullable|string|max:200',
            'title.hy' => 'nullable|string|max:200',
            'title.en' => 'nullable|string|max:200',
            'description.*' => 'nullable|string|max:5000',
            'category_id' => 'nullable|exists:categories,id',
            'location' => 'nullable|string|max:200',
            'event_date' => 'nullable|date',
            'photos.*' => 'image|max:20480',
            'videos.*' => 'mimetypes:video/mp4,video/quicktime,video/webm,video/x-m4v|max:204800',
            'link' => 'nullable|url|max:500',
        ];
    }

    protected function messages(): array
    {
        return ['title.ru.required_without_all' => 'Укажите название хотя бы на одном языке.'];
    }

    public function updatedPhotos(): void
    {
        $this->validateOnly('photos.*');
    }

    public function updatedVideos(): void
    {
        $this->validateOnly('videos.*');
    }

    public function removeUpload(string $field, int $index): void
    {
        abort_unless(in_array($field, ['photos', 'videos'], true), 400);
        array_splice($this->{$field}, $index, 1);
    }

    public function save()
    {
        $this->validate();

        $isNew = ! $this->work;
        $work = $this->work ?? new Work;
        $work->fill([
            'title' => array_map('trim', $this->title),
            'description' => array_map('trim', $this->description),
            'category_id' => $this->category_id ?: null,
            'location' => $this->location ?: null,
            'event_date' => $this->event_date ?: null,
            'is_featured' => $this->is_featured,
            'is_published' => $this->is_published,
        ])->save();

        $disk = config('filesystems.media_disk');
        $position = (int) $work->media()->max('position') + 1;

        foreach ($this->photos as $photo) {
            $work->media()->create(['type' => 'image', 'path' => $photo->store('works/'.$work->id, $disk), 'position' => $position++]);
        }
        foreach ($this->videos as $video) {
            $work->media()->create(['type' => 'video', 'path' => $video->store('works/'.$work->id, $disk), 'position' => $position++]);
        }
        if ($this->link) {
            $media = new WorkMedia(['url' => $this->link]);
            $type = $media->embedUrl() ? 'embed' : (preg_match('/\.(mp4|webm|mov)(\?|$)/i', $this->link) ? 'video' : 'image');
            $work->media()->create(['type' => $type, 'url' => $this->link, 'position' => $position++]);
        }

        foreach ($work->media as $media) {
            if (array_key_exists($media->id, $this->captions) && $media->caption !== ($this->captions[$media->id] ?: null)) {
                $media->update(['caption' => $this->captions[$media->id] ?: null]);
            }
        }

        $this->reset('photos', 'videos', 'link');

        if ($isNew) {
            session()->flash('saved', 'Работа создана. Можно добавить ещё фото или видео.');

            return $this->redirectRoute('admin.works.edit', $work, navigate: true);
        }

        $this->work = $work->fresh('media');
        $this->captions = $this->work->media->pluck('caption', 'id')->map(fn ($c) => (string) $c)->all();
        $this->dispatch('toast', message: 'Сохранено');

        return null;
    }

    public function move(int $mediaId, int $direction): void
    {
        $items = $this->work->media()->get()->values();
        $index = $items->search(fn ($m) => $m->id === $mediaId);
        $target = $index + $direction;
        if ($index === false || $target < 0 || $target >= $items->count()) {
            return;
        }
        $ordered = $items->all();
        [$ordered[$index], $ordered[$target]] = [$ordered[$target], $ordered[$index]];
        foreach ($ordered as $position => $media) {
            $media->update(['position' => $position]);
        }
        $this->work->load('media');
    }

    public function makeCover(int $mediaId): void
    {
        $this->work->media()->whereKey($mediaId)->update(['position' => -1]);
        $this->work->media()->get()->values()->each(fn ($m, $i) => $m->update(['position' => $i]));
        $this->work->load('media');
        $this->dispatch('toast', message: 'Обложка обновлена');
    }

    public function deleteMedia(int $mediaId): void
    {
        $this->work->media()->findOrFail($mediaId)->delete();
        $this->work->load('media');
        unset($this->captions[$mediaId]);
    }

    public function render()
    {
        return view('livewire.admin.work-form', [
            'categories' => Category::orderBy('position')->get(),
        ])->title($this->work ? 'Редактирование работы' : 'Новая работа');
    }
}
