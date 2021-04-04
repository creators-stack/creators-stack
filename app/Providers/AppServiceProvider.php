<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Settings::class, fn (): Settings => Settings::firstOrNew());

        Str::macro('trimSlashes', function (string $str) {
            return implode('/', array_filter(explode('/', $str)));
        });

        Validator::extend('content_path', function (string $attribute, string $value, array $parameters, $validaton) {
            return ! in_array('..', explode('/', $value), true) && File::isDirectory(Storage::disk('content')->path($value));
        });
    }
}
