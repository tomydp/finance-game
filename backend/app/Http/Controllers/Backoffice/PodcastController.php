<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Podcast\PodcastStoreRequest;
use App\Http\Requests\Podcast\PodcastUpdateRequest;
use App\Models\Podcast;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $status  = $request->input('status');
        $search  = $request->input('search');

        $query = Podcast::query()
            ->withCount('episodes')
            ->with('creator');

        if ($status) {
            $query->status($status);
        }

        if ($search) {
            $term = '%'.mb_strtolower($search).'%';
            $query->whereRaw('LOWER(title) LIKE ?', [$term]);
        }

        $podcasts = $query
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(max($perPage, 1));

        return response()->json($podcasts);
    }

    public function store(PodcastStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()?->id;

        $podcast = Podcast::create($data);

        return response()->json(
            $podcast->load('creator')->loadCount('episodes'),
            201
        );
    }

    public function show(Podcast $podcast): JsonResponse
    {
        $podcast->load(['creator', 'episodes' => function ($query) {
            $query->orderByDesc('published_at')
                ->orderByDesc('created_at');
        }])->loadCount('episodes');

        return response()->json($podcast);
    }

    public function update(PodcastUpdateRequest $request, Podcast $podcast): JsonResponse
    {
        $podcast->update($request->validated());

        return response()->json(
            $podcast->load('creator')->loadCount('episodes')
        );
    }

    public function destroy(Podcast $podcast): JsonResponse
    {
        $podcast->delete();

        return response()->json(null, 204);
    }
}
