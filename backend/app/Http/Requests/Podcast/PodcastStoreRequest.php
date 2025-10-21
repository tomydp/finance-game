<?php

namespace App\Http\Requests\Podcast;

use App\Models\Podcast;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PodcastStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'           => ['required', 'string', 'max:255'],
            'slug'            => ['required', 'string', 'max:255', Rule::unique('podcasts', 'slug')],
            'description_md'  => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'string', 'max:2048'],
            'status'          => ['required', Rule::in(Podcast::STATUSES)],
            'published_at'    => ['nullable', 'date'],
        ];
    }
}
