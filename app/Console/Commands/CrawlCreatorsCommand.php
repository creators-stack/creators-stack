<?php

namespace App\Console\Commands;

use App\Models\Creator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CrawlCreatorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:creators {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl creators form root path';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $creators = Storage::disk('content')->directories($this->argument('path'));

        if ($creators < 0) {
            $this->line('No creators found.');

            return 0;
        }

        $this->line(sprintf('Crawling %d creators.', count($creators)));

        $this->withProgressBar($creators, function (string $path) {
            $this->createCreator($path);
        });

        return 0;
    }

    /**
     * @param string $path
     */
    protected function createCreator(string $path)
    {
        $creator = Creator::firstOrNew([
            'root_folder' => $path,
        ]);

        $creator->fill([
            'name' => Str::afterLast($path, '/'),
            'username' => Str::afterLast($path, '/'),
        ]);

        $creator->save();
    }
}
