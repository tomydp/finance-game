<?php

namespace App\Livewire;

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPodcast extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    protected string $pageName = 'podcastsPage';

    public string $basePath = '/backoffice/podcasts';

    public ?string $search = null;
    public ?string $statusFilter = null;

    public array $statusOptions = Podcast::STATUSES;

    protected $listeners = [
        'podcastCreated' => '$refresh',
        'podcastUpdated' => '$refresh',
        'podcastDeleted' => '$refresh',
    ];

    public function mount(): void
    {
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

    public function deletePodcast(int $podcastId): void
    {
        $podcast = Podcast::find($podcastId);

        if ($podcast) {
            $podcast->delete();
            $this->dispatch('podcastDeleted', id: $podcastId);
        }
    }

    public function render()
    {
        $query = Podcast::query()
            ->withCount('episodes')
            ->with('creator')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $query = $this->applyFilters($query);

        $podcasts = $query->paginate(10, ['*'], $this->pageName);
        $podcasts->withPath($this->basePath);

        return view('livewire.show-podcast', [
            'podcasts' => $podcasts,
        ]);
    }

    protected function applyFilters(Builder $query): Builder
    {
        if ($this->statusFilter) {
            $query->status($this->statusFilter);
        }

        if ($this->search) {
            $term = '%'.mb_strtolower($this->search).'%';
            $query->whereRaw('LOWER(title) LIKE ?', [$term]);
        }

        return $query;
    }
}
