<?php

namespace App\Jobs;

use App\Models\File;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Filters\Frame\CustomFrameFilter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateVideoThumbnail implements ShouldQueue, ShouldBeUnique
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
        return $this->file->path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = Storage::disk('content')->path($this->file->path);

        /**
         * Duration of the video in seconds.
         */
        $duration = FFProbe::create()
            ->format($path)
            ->get('duration');

        $dir = 'thumbnails/videos/';

        Storage::disk('public')->makeDirectory($dir);

        $this->file->thumbnail = sprintf('%s%s.jpg', $dir, md5(uniqid('hash', true)));
        $thumbnail_path = Storage::disk('public')->path($this->file->thumbnail);

        FFMpeg::create()
            ->open($path)
            ->frame(TimeCode::fromSeconds($duration / 2))
            ->addFilter(new CustomFrameFilter('scale=896:504:force_original_aspect_ratio=decrease,pad=896:504:-1:-1:color=black'))
            ->save($thumbnail_path);

        $this->file->save();
    }
}
