<?php

namespace App\Models;

use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Class File.
 *
 * @property int id
 * @property string path
 * @property string filename
 * @property string hash
 * @property string thumbnail
 * @property string preview
 * @property ContentType content_type
 * @property int creator_id
 * @property int size
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Collection<View> views
 */
class File extends Model
{
    public const PREVIEW_WIDTH = 896;
    public const PREVIEW_HEIGHT = 504;
    public const MIME_TYPE_CACHE_KEY = 'MIME_TYPE_';

    protected $fillable = [
        'hash',
    ];

    protected $casts = [
        'content_type' => ContentType::class,
    ];

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class, 'creator_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class)
            ->orderByDesc('created_at');
    }

    public function getMimeTypeAttribute()
    {
        return Cache::rememberForever(self::MIME_TYPE_CACHE_KEY.$this->id, function () {
            return Storage::disk('content')->mimeType($this->path);
        });
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeImages(Builder $query)
    {
        return $query->where('content_type', ContentType::IMAGE);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeVideos(Builder $query)
    {
        return $query->where('content_type', ContentType::VIDEO);
    }
}
