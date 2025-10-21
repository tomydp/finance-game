<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreatePodcastEpisode extends Component
{
    public Podcast $podcast;

    public bool $showModal = false;

    public string $title = '';
    public string $slug = '';
    public ?string $summary = null;
    public ?string $description_md = null;
    public ?string $transcript_md = null;
    public string $audio_url = '';
    public ?int $duration_seconds = null;
    public string $status = PodcastEpisode::STATUS_DRAFT;
    public ?string $published_at = null;
    public ?string $scheduled_for = null;

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
            'summary'          => ['nullable', 'string'],
            'description_md'   => ['nullable', 'string'],
            'transcript_md'    => ['nullable', 'string'],
            'audio_url'        => ['required', 'string', 'max:2048'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'status'           => ['required', Rule::in(PodcastEpisode::STATUSES)],
            'published_at'     => ['nullable', 'date'],
            'scheduled_for'    => ['nullable', 'date'],
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
        $this->showModal = false;
    }

    public function save(): void
    {
        $data = $this->validate();

        $episode = PodcastEpisode::create([
            'podcast_id'       => $this->podcast->id,
            'title'            => $data['title'],
            'slug'             => $data['slug'],
            'summary'          => $data['summary'] ?? null,
            'description_md'   => $data['description_md'] ?? null,
            'transcript_md'    => $data['transcript_md'] ?? null,
            'audio_url'        => $data['audio_url'],
            'duration_seconds' => $data['duration_seconds'] ?? null,
            'status'           => $data['status'],
            'published_at'     => $this->normalizeDate($data['published_at'] ?? null),
            'scheduled_for'    => $this->normalizeDate($data['scheduled_for'] ?? null),
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
        $this->summary = null;
        $this->description_md = null;
        $this->transcript_md = null;
        $this->audio_url = '';
        $this->duration_seconds = null;
        $this->status = PodcastEpisode::STATUS_DRAFT;
        $this->published_at = null;
        $this->scheduled_for = null;
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
