<?php

namespace App\Http\Requests\Podcast;

use App\Models\Podcast;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PodcastUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $podcast = $this->route('podcast');
        $podcastId = $podcast instanceof Podcast ? $podcast->getKey() : $podcast;

        return [
            'title'           => ['sometimes', 'required', 'string', 'max:255'],
            'slug'            => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('podcasts', 'slug')->ignore($podcastId),
            ],
            'description_md'  => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'string', 'max:2048'],
            'status'          => ['sometimes', 'required', Rule::in(Podcast::STATUSES)],
            'published_at'    => ['nullable', 'date'],
        ];
    }
}
