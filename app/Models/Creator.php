<?php

namespace App\Models;

use App\Helpers\FileSystemHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class Creator.
 *
 * @property int id
 * @property string name
 * @property string username
 * @property string root_folder
 * @property string profile_picture
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Creator extends Model
{
    protected $fillable = [
        'name',
        'username',
        'root_folder',
        'profile_picture',
    ];

    /**
     * @return HasMany
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'creator_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->files()
            ->where('content_type_id', ContentType::IMAGE)
            ->whereNotNull('thumbnail');
    }

    /**
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->files()
            ->where('content_type_id', ContentType::VIDEO)
            ->whereNotNull('thumbnail');
    }

    public function setUsernameAttribute(?string $username)
    {
        $this->attributes['username'] = $username === null ? $username : Str::slug($username);
    }

    public function getRootFolderAttribute()
    {
        return array_key_exists('root_folder', $this->attributes) ? sprintf('/%s/', $this->attributes['root_folder']) : null;
    }

    public function setRootFolderAttribute(?string $path)
    {
        $this->attributes['root_folder'] = $path === null ? $path : Str::trimSlashes($path);
    }

    public function profilePictureUrl($small = false)
    {
        if (empty($this->profile_picture)) {
            return $small ? asset('img/error_not_found_small.jpg') : asset('img/error_not_found.jpg');
        }

        return Storage::disk('public')->url($this->profile_picture);
    }

    public function totalHumanSize()
    {
        return FileSystemHelper::humanSize($this->files()->sum('size'));
    }
}
