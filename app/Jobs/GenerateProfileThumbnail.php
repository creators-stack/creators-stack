<?php

namespace App\Jobs;

use App\Models\Creator;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GenerateProfileThumbnail implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Creator $creator, protected string $file_path)
    {
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return md5($this->creator->id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = Storage::disk('public');

        $thumbnail_name = md5(uniqid('hash', ''));
        $thumbnail_dir_path = 'thumbnails/profile_pictures';
        $thumbnail_file_path = $thumbnail_dir_path . '/'. $thumbnail_name . '.jpg';

        $disk->makeDirectory($thumbnail_dir_path);

        $final_path = Storage::disk('public')->path($thumbnail_file_path);

        Image::make($this->file_path)
            ->fit(File::PREVIEW_HEIGHT, File::PREVIEW_WIDTH)
            ->save($final_path, 90, 'jpg');

        $this->creator->profile_picture = $thumbnail_file_path;
        $this->creator->save();
    }
}
