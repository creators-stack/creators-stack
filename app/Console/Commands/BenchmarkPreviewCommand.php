<?php

namespace App\Console\Commands;

use App\Jobs\GenerateVideoPreview;
use App\Models\File;
use Illuminate\Console\Command;

class BenchmarkPreviewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preview:benchmark {path} {preset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $file = File::where('path', $this->argument('path'))->first();

        if (! $file) {
            $this->error('No file found for the given path');

            return 1;
        }

        $this->info('Benchmarking...');

        $start = now();
        GenerateVideoPreview::dispatchSync($file, 5, 2000);
        $end = now();

        $this->info('Completed in '.$start->diffInSeconds($end).' seconds');

        return 0;
    }
}
