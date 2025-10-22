<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePodcastEpisode extends Component
{
    use WithFileUploads;

    public Podcast $podcast;

    public bool $showModal = false;

    public string $title = '';
    public string $slug = '';
    public ?string $description_md = null;
    public ?string $transcript_md = null;
    public ?string $audio_url = null;
    public $audioUpload = null;
    public ?int $duration_seconds = null;
    public string $status = PodcastEpisode::STATUS_DRAFT;
    public ?string $published_at = null;

    public array $selectedCourses = [];
    public array $selectedLessons = [];

    public array $statusOptions = PodcastEpisode::STATUSES;

    public $courses = [];
    public $lessons = [];

    public function mount(Podcast $podcast): void
    {
        $this->podcast = $podcast;
        $this->loadOptions();
    }

    protected function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => [
                'required',
                'string',
                'max:255',
                Rule::unique('podcast_episodes', 'slug')->where(
                    fn ($q) => $q->where('podcast_id', $this->podcast->id)
                ),
            ],
            'description_md'   => ['nullable', 'string'],
            'transcript_md'    => ['nullable', 'string'],
            'audio_url'        => ['nullable', 'string', 'max:2048'],
            'audioUpload'      => ['nullable', 'file', 'mimetypes:audio/mpeg,audio/mp4,audio/x-m4a,audio/wav,audio/x-wav,audio/flac', 'max:102400'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'status'           => ['required', Rule::in(PodcastEpisode::STATUSES)],
            'published_at'     => ['nullable', 'date'],
            'selectedCourses'  => ['array'],
            'selectedCourses.*'=> ['integer', 'exists:courses,id'],
            'selectedLessons'  => ['array'],
            'selectedLessons.*'=> ['integer', 'exists:lessons,id'],
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
        $this->audioUpload = null;
        $this->showModal = false;
    }

    public function save(): void
    {
        $data = $this->validate();

        $audioUrl = $data['audio_url'] ?? null;

        if ($this->audioUpload) {
            $path = $this->audioUpload->store('podcasts/episodes/audio', 'public');
            $audioUrl = Storage::disk('public')->url($path);
            $this->audioUpload = null;
        }

        if (!$audioUrl) {
            $this->addError('audio_url', 'Debes proporcionar un archivo o una URL de audio.');
            return;
        }

        $episode = PodcastEpisode::create([
            'podcast_id'       => $this->podcast->id,
            'title'            => $data['title'],
            'slug'             => $data['slug'],
            'description_md'   => $data['description_md'] ?? null,
            'transcript_md'    => $data['transcript_md'] ?? null,
            'audio_url'        => $audioUrl,
            'duration_seconds' => $data['duration_seconds'] ?? null,
            'status'           => $data['status'],
            'published_at'     => $this->normalizeDate($data['published_at'] ?? null),
            'created_by'       => auth()->id(),
        ]);

        $episode->courses()->sync($this->normalizeIds($data['selectedCourses'] ?? []));
        $episode->lessons()->sync($this->normalizeIds($data['selectedLessons'] ?? []));

        $this->dispatch('podcastEpisodeCreated', id: $episode->id);

        $this->closeModal();
    }

    private function loadOptions(): void
    {
        $this->courses = Course::orderBy('name')->get(['id', 'name']);
        $this->lessons = Lesson::orderBy('title')->get(['id', 'title']);
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->slug = '';
        $this->description_md = null;
        $this->transcript_md = null;
        $this->audio_url = null;
        $this->audioUpload = null;
        $this->duration_seconds = null;
        $this->status = PodcastEpisode::STATUS_DRAFT;
        $this->published_at = null;
        $this->selectedCourses = [];
        $this->selectedLessons = [];
    }

    private function normalizeDate(?string $value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        return Carbon::parse($value);
    }

    private function normalizeIds(array $values): array
    {
        return array_values(array_filter(array_map('intval', $values)));
    }

    public function render()
    {
        return view('livewire.create-podcast-episode', [
            'podcast' => $this->podcast,
            'courses' => $this->courses,
            'lessons' => $this->lessons,
        ]);
    }
}
