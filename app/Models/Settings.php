<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class Setting.
 *
 * @property int id
 * @property bool crawl_based_on_file_extension
 * @property bool generate_videos_preview
 * @property bool mute_videos_preview
 * @property ?int videos_preview_parts_count
 * @property ?int videos_preview_parts_length
 * @property array image_extensions
 * @property array video_extensions
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Settings extends Model
{
    protected $casts = [
        'crawl_based_on_file_extension' => 'boolean',
        'image_extensions' => 'array',
        'video_extensions' => 'array',
    ];

    protected $attributes = [
        'crawl_based_on_file_extension' => true,
        'image_extensions' => '["jpg","jpeg","png","gif"]',
        'video_extensions' => '["avi","mp4","mkv","m4v","mov","webm","wmv","3gp"]',
        'mute_videos_preview' => true,
        'generate_videos_preview' => false,
        'videos_preview_parts_count' => 5,
        'videos_preview_parts_length' => 500,
    ];
}
