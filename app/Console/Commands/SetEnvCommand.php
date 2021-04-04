<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:set {key} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set environment Key/Value pair';

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
        $this->setEnvValue($this->argument('key'), $this->argument('value'));
        $this->info(sprintf('env key %s set to %s', $this->argument('key'), $this->argument('value')));

        return 0;
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function setEnvValue(string $key, string $value)
    {
        $path = $this->laravel->environmentFilePath();
        $env = file_get_contents($path);

        $old_value = env($key);

        if (! str_contains($env, $key.'=')) {
            $env .= sprintf("%s=%s\n", $key, $value);
        } elseif ($old_value) {
            $env = str_replace(sprintf('%s=%s', $key, $old_value), sprintf('%s=%s', $key, $value), $env);
        } else {
            $env = str_replace(sprintf('%s=', $key), sprintf('%s=%s', $key, $value), $env);
        }

        file_put_contents($path, $env);
    }
}
