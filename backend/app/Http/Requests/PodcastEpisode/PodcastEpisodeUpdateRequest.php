<?php

namespace App\Http\Requests\PodcastEpisode;

use App\Models\PodcastEpisode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PodcastEpisodeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $podcast = $this->route('podcast');
        $episode = $this->route('episode') ?? $this->route('podcast_episode');

        $podcastId = $this->input('podcast_id')
            ?? ($podcast instanceof \App\Models\Podcast ? $podcast->getKey() : $podcast)
            ?? ($episode instanceof PodcastEpisode ? $episode->podcast_id : null);

        if ($podcastId) {
            $this->merge(['podcast_id' => $podcastId]);
        }
    }

    public function rules(): array
    {
        $episode  = $this->route('episode') ?? $this->route('podcast_episode');
        $episodeId = $episode instanceof PodcastEpisode ? $episode->getKey() : $episode;
        $podcastId = $this->input('podcast_id');

        return [
            'title'           => ['sometimes', 'required', 'string', 'max:255'],
            'slug'            => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('podcast_episodes', 'slug')
                    ->ignore($episodeId)
                    ->where(fn ($q) => $q->where('podcast_id', $podcastId)),
            ],
            'summary'         => ['nullable', 'string'],
            'description_md'  => ['nullable', 'string'],
            'transcript_md'   => ['nullable', 'string'],
            'audio_url'       => ['sometimes', 'required', 'string', 'max:2048'],
            'duration_seconds'=> ['nullable', 'integer', 'min:0'],
            'status'          => ['sometimes', 'required', Rule::in(PodcastEpisode::STATUSES)],
            'published_at'    => ['nullable', 'date'],
            'scheduled_for'   => ['nullable', 'date'],
            'course_ids'      => ['sometimes', 'array'],
            'course_ids.*'    => ['integer', 'exists:courses,id'],
            'lesson_ids'      => ['sometimes', 'array'],
            'lesson_ids.*'    => ['integer', 'exists:lessons,id'],
        ];
    }
}
