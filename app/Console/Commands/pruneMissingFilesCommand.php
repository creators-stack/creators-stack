<?php

namespace App\Console\Commands;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class pruneMissingFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune the database files not present on disk';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $disk = Storage::disk('content');
        $total = 0;

        File::chunk(200, function(Collection $files) use ($disk, &$total) {
            $total += $files->count();

            foreach ($files as $file) {
                if ($disk->exists($file->path) === false) {
                    Log::info(sprintf('Pruning %s from database and generated content because the file was not found on the disk', $file->path));
                    $file->delete();
                }
            }
        });

        $this->info(sprintf('Deleted %d files from database', $total));

        return 0;
    }
}
