<?php

namespace App\Console\Commands;

use App\Jobs\SyncFile;
use App\Models\Creator;
use App\Models\Settings;
use Closure;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CrawlFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:files {creator?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl creator files';

    protected Settings $settings;
    protected Filesystem $disk;
    protected Collection $creators;
    protected Collection $files;
    protected array $jobs = [];
    protected string $batch_name;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->disk = Storage::disk('content');
        $this->files = collect();
        $this->creators = collect();
    }

    /**
     * Execute the console command.
     *
     * @param Settings $settings
     *
     * @return int
     */
    public function handle(Settings $settings)
    {
        $this->settings = $settings;

        if ($this->batchAlreadyRunning()) {
            return 1;
        }

        if (empty($this->argument('creator'))) {
            $this->batch_name = 'crawl_creators';
            $this->creators = Creator::all();
        } else {
            $creator = Creator::where('username', $this->argument('creator'))->firstOrFail();
            $this->batch_name = 'crawl_creator_'.$creator->username;
            $this->creators = $this->creators->push($creator);
        }

        $this->line(sprintf('Crawling %d creators files', $this->creators->count()));

        $this->creators->each(Closure::fromCallable([$this, 'crawlFiles']));

        $this->setJobs();
        $this->dispatchJobs();

        return 0;
    }

    /**
     * @return bool
     */
    protected function batchAlreadyRunning(): bool
    {
        return DB::table('job_batches')
            ->whereNull('cancelled_at')
            ->whereNull('finished_at')
            ->count() > 0;
    }

    /**
     * @param Creator $creator
     */
    protected function crawlFiles(Creator $creator)
    {
        $this->files->put($creator->id, $this->disk->allFiles($creator->root_folder));
    }

    protected function setJobs()
    {
        $this->files->each(function ($creator_files, $creator_id) {
            while (count($creator_files) > 0) {
                $path = array_pop($creator_files);

                $this->jobs[] = new SyncFile($creator_id, $path);

                unset($path);
            }

            $this->files->offsetUnset($creator_id);
        });
    }

    protected function dispatchJobs()
    {
        if (count($this->jobs) > 0) {
            $batch = Bus::batch($this->jobs)
                ->name($this->batch_name)
                ->allowFailures()
                ->dispatch();

            $this->info(sprintf('Dispatched job with id: %s', $batch->id));
        } else {
            $this->info('No jobs to dispatch !');
        }
    }
}
