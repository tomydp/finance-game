<?php

namespace App\Livewire;

use App\Models\Podcast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePodcast extends Component
{
    use WithFileUploads;

    public bool $showModal = false;

    public string $title = '';
    public string $slug = '';
    public ?string $description_md = null;
    public ?string $cover_image_url = null;
    public string $status = Podcast::STATUS_DRAFT;
    public ?string $published_at = null;
    public $coverImageUpload = null;

    public array $statusOptions = Podcast::STATUSES;

    protected function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['required', 'string', 'max:255', Rule::unique('podcasts', 'slug')],
            'description_md'   => ['nullable', 'string'],
            'cover_image_url'  => ['nullable', 'string', 'max:2048'],
            'coverImageUpload' => ['nullable', 'image', 'max:2048'],
            'status'           => ['required', Rule::in(Podcast::STATUSES)],
            'published_at'     => ['nullable', 'date'],
        ];
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    public function save(): void
    {
        $data = $this->validate();

        $coverUrl = $data['cover_image_url'] ?? null;

        if ($this->coverImageUpload) {
            $path = $this->coverImageUpload->store('podcasts/covers', 'public');
            $coverUrl = Storage::disk('public')->url($path);
        }

        $podcast = Podcast::create([
            'title'           => $data['title'],
            'slug'            => $data['slug'],
            'description_md'  => $data['description_md'] ?? null,
            'cover_image_url' => $coverUrl,
            'status'          => $data['status'],
            'published_at'    => $this->normalizeDate($data['published_at'] ?? null),
            'created_by'      => auth()->id(),
        ]);

        $this->dispatch('podcastCreated', id: $podcast->id);

        $this->closeModal();
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->slug = '';
        $this->description_md = null;
        $this->cover_image_url = null;
        $this->coverImageUpload = null;
        $this->status = Podcast::STATUS_DRAFT;
        $this->published_at = null;
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
        return view('livewire.create-podcast');
    }
}
