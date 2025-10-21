<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\PodcastEpisode\PodcastEpisodeStoreRequest;
use App\Http\Requests\PodcastEpisode\PodcastEpisodeUpdateRequest;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PodcastEpisodeController extends Controller
{
    public function index(Request $request, Podcast $podcast): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $status  = $request->input('status');
        $search  = $request->input('search');

        $query = $podcast->episodes()->with(['courses:id,name', 'lessons:id,title']);

        if ($status) {
            $query->status($status);
        }

        if ($search) {
            $query->search($search);
        }

        $episodes = $query
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(max($perPage, 1));

        return response()->json($episodes);
    }

    public function store(
        PodcastEpisodeStoreRequest $request,
        Podcast $podcast
    ): JsonResponse {
        $data = $request->validated();
        $data['podcast_id'] = $podcast->id;
        $data['created_by'] = $request->user()?->id;

        $episode = PodcastEpisode::create($data);

        $this->syncRelationships($episode, $request);

        return response()->json(
            $episode->load(['courses:id,name', 'lessons:id,title', 'podcast', 'creator']),
            201
        );
    }

    public function show(Podcast $podcast, PodcastEpisode $episode): JsonResponse
    {
        $this->ensureEpisodeBelongsToPodcast($episode, $podcast);

        $episode->load(['courses:id,name', 'lessons:id,title', 'podcast', 'creator']);

        return response()->json($episode);
    }

    public function update(
        PodcastEpisodeUpdateRequest $request,
        Podcast $podcast,
        PodcastEpisode $episode
    ): JsonResponse {
        $this->ensureEpisodeBelongsToPodcast($episode, $podcast);

        $data = $request->validated();
        $episode->update($data);

        $this->syncRelationships($episode, $request);

        return response()->json(
            $episode->load(['courses:id,name', 'lessons:id,title', 'podcast', 'creator'])
        );
    }

    public function destroy(Podcast $podcast, PodcastEpisode $episode): JsonResponse
    {
        $this->ensureEpisodeBelongsToPodcast($episode, $podcast);

        $episode->delete();

        return response()->json(null, 204);
    }

    protected function syncRelationships(PodcastEpisode $episode, Request $request): void
    {
        if ($request->has('course_ids')) {
            $episode->courses()->sync($request->input('course_ids', []));
        }

        if ($request->has('lesson_ids')) {
            $episode->lessons()->sync($request->input('lesson_ids', []));
        }
    }

    protected function ensureEpisodeBelongsToPodcast(
        PodcastEpisode $episode,
        Podcast $podcast
    ): void {
        if ((int) $episode->podcast_id !== (int) $podcast->id) {
            abort(404);
        }
    }
}
