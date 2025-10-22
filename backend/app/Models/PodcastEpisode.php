<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PodcastEpisode extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DRAFT     = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_PRIVATE   = 'private';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISHED,
        self::STATUS_PRIVATE,
    ];

    protected $fillable = [
        'podcast_id',
        'title',
        'slug',
        'description_md',
        'transcript_md',
        'audio_url',
        'duration_seconds',
        'status',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'status'           => 'string',
        'published_at'     => 'datetime',
    ];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_podcast_episode')
            ->withTimestamps();
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_podcast_episode')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if ($status && in_array($status, self::STATUSES, true)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        $term = mb_strtolower($term);

        return $query->where(function (Builder $builder) use ($term) {
            $builder->whereRaw('LOWER(title) LIKE ?', ['%'.$term.'%'])
                ->orWhereRaw('LOWER(description_md) LIKE ?', ['%'.$term.'%']);
        });
    }
}
