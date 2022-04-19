<?php

namespace App\Jobs;

use App\Models\Creator;
use App\Services\GalleryDl\GallerydlWrapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ImportContentFromUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public int $backoff = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private int $creatorId)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(GallerydlWrapper $gallerydl)
    {
        /** @var Creator $creator */
        $creator = Creator::findOrFail($this->creatorId);

        $url = $creator->url;
        $destination = Storage::disk('content')->path($creator->root_folder);
        $command = sprintf('php artisan sync:file %d {}', $creator->id);

        Log::info(implode(' ', $gallerydl
            ->setUrl($url)
            ->destination($destination)
            ->exec($command)
            ->debug()));

        $result = $gallerydl
            ->setUrl($url)
            ->destination($destination)
            ->exec($command)
            ->run();

        if (! $result->isSuccessful()) {
            $this->fail(
                new RuntimeException(
                    sprintf(
                        "gallery-dl process failed\nexit code: %d\nstderr: %s\nstdout: %s\n",
                        $result->exitCode(),
                        $result->stderr(),
                        $result->stdout(),
                    )
                )
            );
        } elseif (! empty($result->stderr())) {
            Log::warning('gallery-dl process stderr: '.$result->stderr());
        }
    }
}
