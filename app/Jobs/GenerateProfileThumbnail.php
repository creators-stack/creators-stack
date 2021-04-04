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
    public function __construct(protected Creator $creator, protected File $file)
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
        $full_path = Storage::disk('content')->path($this->file->path);

        $stream = Image::make($full_path)
            ->fit(File::PREVIEW_HEIGHT, File::PREVIEW_WIDTH)
            ->encode('jpg');

        $thumbnail_name = md5(uniqid('hash', ''));
        $thumbnail_path = 'thumbnails/profile_pictures/'.$thumbnail_name.'.jpg';

        Storage::disk('public')->put($thumbnail_path, $stream);

        $this->creator->profile_picture = $thumbnail_path;
        $this->creator->save();
    }
}
