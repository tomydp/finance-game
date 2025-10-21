<?php

namespace App\Http\Requests\PodcastEpisode;

use App\Models\PodcastEpisode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PodcastEpisodeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $podcast = $this->route('podcast');
        $podcastId = $this->input('podcast_id')
            ?? ($podcast instanceof \App\Models\Podcast ? $podcast->getKey() : $podcast);

        if ($podcastId) {
            $this->merge(['podcast_id' => $podcastId]);
        }
    }

    public function rules(): array
    {
        $podcastId = $this->input('podcast_id');

        return [
            'podcast_id'      => ['required', 'exists:podcasts,id'],
            'title'           => ['required', 'string', 'max:255'],
            'slug'            => [
                'required',
                'string',
                'max:255',
                Rule::unique('podcast_episodes', 'slug')->where(fn ($q) => $q->where('podcast_id', $podcastId)),
            ],
            'summary'         => ['nullable', 'string'],
            'description_md'  => ['nullable', 'string'],
            'transcript_md'   => ['nullable', 'string'],
            'audio_url'       => ['required', 'string', 'max:2048'],
            'duration_seconds'=> ['nullable', 'integer', 'min:0'],
            'status'          => ['required', Rule::in(PodcastEpisode::STATUSES)],
            'published_at'    => ['nullable', 'date'],
            'scheduled_for'   => ['nullable', 'date'],
            'course_ids'      => ['sometimes', 'array'],
            'course_ids.*'    => ['integer', 'exists:courses,id'],
            'lesson_ids'      => ['sometimes', 'array'],
            'lesson_ids.*'    => ['integer', 'exists:lessons,id'],
        ];
    }
}
