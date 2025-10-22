<?php

namespace App\Livewire;

use App\Models\Podcast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPodcast extends Component
{
    use WithFileUploads;

    public int $podcastId;
    public bool $isOpen = false;

    public string $title = '';
    public string $slug = '';
    public ?string $description_md = null;
    public ?string $cover_image_url = null;
    public string $status = Podcast::STATUS_DRAFT;
    public ?string $published_at = null;
    public $coverImageUpload = null;

    public array $statusOptions = Podcast::STATUSES;

    public function mount(int $podcastId): void
    {
        $this->podcastId = $podcastId;
    }

    protected function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => [
                'required',
                'string',
                'max:255',
                Rule::unique('podcasts', 'slug')->ignore($this->podcastId),
            ],
            'description_md'   => ['nullable', 'string'],
            'cover_image_url'  => ['nullable', 'string', 'max:2048'],
            'coverImageUpload' => ['nullable', 'image', 'max:2048'],
            'status'           => ['required', Rule::in(Podcast::STATUSES)],
            'published_at'     => ['nullable', 'date'],
        ];
    }

    public function openModal(): void
    {
        $this->loadFromDb();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->coverImageUpload = null;
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->coverImageUpload = null;
        $this->isOpen = false;
    }

    public function update(): void
    {
        $data = $this->validate();

        $coverUrl = $data['cover_image_url'] ?? $this->cover_image_url;

        if ($this->coverImageUpload) {
            $path = $this->coverImageUpload->store('podcasts/covers', 'public');
            $coverUrl = Storage::disk('public')->url($path);
            $this->coverImageUpload = null;
        }

        Podcast::whereKey($this->podcastId)->update([
            'title'           => $data['title'],
            'slug'            => $data['slug'],
            'description_md'  => $data['description_md'] ?? null,
            'cover_image_url' => $coverUrl,
            'status'          => $data['status'],
            'published_at'    => $this->normalizeDate($data['published_at'] ?? null),
        ]);

        $this->cover_image_url = $coverUrl;

        $this->dispatch('podcastUpdated', id: $this->podcastId);
        $this->closeModal();
    }

    protected function loadFromDb(): void
    {
        $podcast = Podcast::findOrFail($this->podcastId);

        $this->title = $podcast->title;
        $this->slug = $podcast->slug;
        $this->description_md = $podcast->description_md;
        $this->cover_image_url = $podcast->cover_image_url;
        $this->status = $podcast->status;
        $this->published_at = $podcast->published_at
            ? $podcast->published_at->format('Y-m-d\TH:i')
            : null;
    }

    private function normalizeDate(?string $value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        return Carbon::parse($value);
    }

    public function render()
    {
        return view('livewire.edit-podcast');
    }
}
