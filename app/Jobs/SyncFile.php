<?php

namespace App\Jobs;

use App\Enums\ContentType;
use App\Helpers\FileSystemHelper;
use App\Models\Creator;
use App\Models\File;
use App\Models\Settings;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class SyncFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected Settings $settings;
    protected Filesystem $disk;
    protected Creator $creator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected int $creator_id, protected string $path)
    {
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return md5($this->path);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->batch() && $this->batch()->cancelled()) {
            return;
        }

        $this->disk = Storage::disk('content');
        $this->settings = App::make(Settings::class);

        $contentType = match (true) {
            FileSystemHelper::isImage($this->settings, $this->disk, $this->path) => ContentType::IMAGE,
            FileSystemHelper::isVideo($this->settings, $this->disk, $this->path) => ContentType::VIDEO,
            default => null,
        };

        if ($contentType === null) {
            Log::warning(sprintf('Unrecognized file %s', $this->path));
            return;
        }

        $this->creator = Creator::findOrFail($this->creator_id);
        $file = $this->getFile($contentType);

        match ($contentType) {
            ContentType::IMAGE => $this->syncImage($file),
            ContentType::VIDEO => $this->syncVideo($file),
        };
    }

    /**
     * @param Throwable $exception
     *
     * @return void
     */
    public function failed(Throwable $exception): void
    {
        if ($this->batch()) {
            $this->batch()->decrementPendingJobs($this->uniqueId());
        }
    }

    /**
     * @param ContentType $contentType
     *
     * @return File
     */
    protected function getFile(ContentType $contentType): File
    {
        $file = File::firstOrNew([
            'path' => $this->path,
        ]);

        $file->creator()->associate($this->creator);

        $file->content_type = $contentType;
        $file->path = $this->path;
        $file->size = $this->disk->size($this->path);
        $file->filename = Str::afterLast($this->path, DIRECTORY_SEPARATOR);
        $file->hash = ! $file->hash ? md5(uniqid('hash', true)) : $file->hash;

        $file->save();

        return $file;
    }

    /**
     * @param File $file
     *
     * @return void
     */
    protected function syncImage(File $file): void
    {
        if (! $this->creator->profile_picture) {
            GenerateProfileThumbnail::dispatchSync($this->creator, Storage::disk('content')->path($file->path));
        }

        if (! $file->thumbnail) {
            GenerateImageThumbnail::dispatchSync($file);
        }
    }

    /**
     * @param File $file
     *
     * @return void
     */
    protected function syncVideo(File $file): void
    {
        if (! $file->thumbnail) {
            GenerateVideoThumbnail::dispatchSync($file);
        }
        if ($this->settings->generate_videos_preview && ! $file->preview) {
            GenerateVideoPreview::dispatchSync($file, $this->settings);
        }
    }
}
