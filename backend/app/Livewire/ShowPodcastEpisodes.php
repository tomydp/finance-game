<?php

namespace App\Livewire;

use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPodcastEpisodes extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    protected string $pageName = 'episodesPage';

    public Podcast $podcast;
    public string $basePath = '/backoffice/podcasts';

    public ?string $search = null;
    public ?string $statusFilter = null;

    public array $statusOptions = PodcastEpisode::STATUSES;

    protected $listeners = [
        'podcastEpisodeCreated' => '$refresh',
        'podcastEpisodeUpdated' => '$refresh',
        'podcastEpisodeDeleted' => '$refresh',
    ];

    public function mount(Podcast $podcast): void
    {
        $this->podcast = $podcast;
        $this->basePath = url()->current();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function deleteEpisode(int $episodeId): void
    {
        $episode = PodcastEpisode::where('podcast_id', $this->podcast->id)
            ->find($episodeId);

        if ($episode) {
            $episode->delete();
            $this->dispatch('podcastEpisodeDeleted', id: $episodeId);
        }
    }

    public function render()
    {
        $query = PodcastEpisode::query()
            ->where('podcast_id', $this->podcast->id)
            ->with(['courses:id,name', 'lessons:id,title', 'creator'])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $query = $this->applyFilters($query);

        $episodes = $query->paginate(10, ['*'], $this->pageName);
        $episodes->withPath($this->basePath);

        return view('livewire.show-podcast-episodes', [
            'podcast' => $this->podcast,
            'episodes' => $episodes,
        ]);
    }

    protected function applyFilters(Builder $query): Builder
    {
        if ($this->statusFilter) {
            $query->status($this->statusFilter);
        }

        if ($this->search) {
            $term = '%'.mb_strtolower($this->search).'%';
            $query->where(function (Builder $builder) use ($term) {
                $builder->whereRaw('LOWER(title) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(description_md) LIKE ?', [$term]);
            });
        }

        return $query;
    }
}
