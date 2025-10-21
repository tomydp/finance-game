<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\PodcastEpisode;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditPodcastEpisode extends Component
{
    public int $podcastId;
    public int $episodeId;

    public bool $isOpen = false;

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

    public function mount(int $podcastId, int $episodeId): void
    {
        $this->podcastId = $podcastId;
        $this->episodeId = $episodeId;
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
                Rule::unique('podcast_episodes', 'slug')
                    ->ignore($this->episodeId)
                    ->where(fn ($q) => $q->where('podcast_id', $this->podcastId)),
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
        $this->loadFromDb();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isOpen = false;
    }

    public function update(): void
    {
        $data = $this->validate();

        $episode = PodcastEpisode::where('podcast_id', $this->podcastId)
            ->findOrFail($this->episodeId);

        $episode->update([
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
        ]);

        $episode->courses()->sync($this->normalizeIds($data['selectedCourses'] ?? []));
        $episode->lessons()->sync($this->normalizeIds($data['selectedLessons'] ?? []));

        $this->dispatch('podcastEpisodeUpdated', id: $episode->id);
        $this->closeModal();
    }

    private function loadFromDb(): void
    {
        $episode = PodcastEpisode::with(['courses:id', 'lessons:id'])
            ->where('podcast_id', $this->podcastId)
            ->findOrFail($this->episodeId);

        $this->title = $episode->title;
        $this->slug = $episode->slug;
        $this->summary = $episode->summary;
        $this->description_md = $episode->description_md;
        $this->transcript_md = $episode->transcript_md;
        $this->audio_url = $episode->audio_url;
        $this->duration_seconds = $episode->duration_seconds;
        $this->status = $episode->status;
        $this->published_at = $episode->published_at
            ? $episode->published_at->format('Y-m-d\TH:i')
            : null;
        $this->scheduled_for = $episode->scheduled_for
            ? $episode->scheduled_for->format('Y-m-d\TH:i')
            : null;

        $this->selectedCourses = $episode->courses->pluck('id')->all();
        $this->selectedLessons = $episode->lessons->pluck('id')->all();
    }

    private function loadOptions(): void
    {
        $this->courses = Course::orderBy('name')->get(['id', 'name']);
        $this->lessons = Lesson::orderBy('title')->get(['id', 'title']);
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
        return view('livewire.edit-podcast-episode', [
            'courses' => $this->courses,
            'lessons' => $this->lessons,
        ]);
    }
}
