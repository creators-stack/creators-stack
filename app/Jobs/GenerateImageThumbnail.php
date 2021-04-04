<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GenerateImageThumbnail implements ShouldQueue, ShouldBeUnique
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
    public function __construct(protected File $file)
    {
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return md5($this->file->path);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = Storage::disk('content')->path($this->file->path);

        $thumbnail_name = md5(uniqid('hash', ''));
        $thumbnail_path = 'thumbnails/images/'.$thumbnail_name.'.jpg';

        $stream = Image::make($path)
            ->fit(File::PREVIEW_HEIGHT, File::PREVIEW_WIDTH)
            ->encode('jpg');

        Storage::disk('public')->put($thumbnail_path, $stream);

        $this->file->thumbnail = $thumbnail_path;
        $this->file->save();
    }
}
