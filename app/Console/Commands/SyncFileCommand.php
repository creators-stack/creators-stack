<?php

namespace App\Console\Commands;

use App\Jobs\SyncFile;
use Illuminate\Console\Command;

class SyncFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:file {creator_id} {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dispatch(
            new SyncFile(
                (int) $this->argument('creator_id'),
                substr($this->argument('path'), strlen(config('filesystems.disks.content.root')))
            )
        );

        return self::SUCCESS;
    }
}
