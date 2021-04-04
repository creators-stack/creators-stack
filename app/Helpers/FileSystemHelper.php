<?php

namespace App\Helpers;

use App\Models\Settings;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Str;

class FileSystemHelper
{
    /**
     * @param int $bytes
     * @param int $decimals
     *
     * @return string
     */
    public static function humanSize(int $bytes, $decimals = 2)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("<span class='font-bold'>%.*f</span> %s", $decimals, $bytes / pow(1024, $factor), $sizes[$factor]);
    }

    /**
     * @param Settings $settings
     * @param Filesystem $disk
     * @param string $path
     *
     * @return bool
     */
    public static function isImage(Settings $settings, Filesystem $disk, string $path): bool
    {
        if ($settings->crawl_based_on_file_extension === true) {
            $targeted_extensions = $settings->image_extensions;

            return Str::endsWith(Str::lower($path), $targeted_extensions);
        }

        $mime_type = $disk->mimeType($path);

        return Str::before(Str::lower($mime_type), '/') === 'image';
    }

    /**
     * @param Settings $settings
     * @param Filesystem $disk
     * @param string $path
     *
     * @return bool
     */
    public static function isVideo(Settings $settings, Filesystem $disk, string $path): bool
    {
        if ($settings->crawl_based_on_file_extension === true) {
            $targeted_extensions = $settings->video_extensions;

            return Str::endsWith(Str::lower($path), $targeted_extensions);
        }

        $mime_type = $disk->mimeType($path);

        return Str::before(Str::lower($mime_type), '/') === 'video';
    }
}
