<?php

namespace App\Providers;

use App\Models\Settings;
use App\Services\GalleryDl\GallerydlWrapper;
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

        $this->app->bind(
            GallerydlWrapper::class,
            fn (): GallerydlWrapper => new GallerydlWrapper(config('app.path'))
        );

        Str::macro('trimSlashes', function (string $str) {
            return implode('/', array_filter(explode('/', $str)));
        });

        Validator::extend('content_path', function (string $attribute, string $value) {
            return ! in_array('..', explode('/', $value), true) && File::isDirectory(Storage::disk('content')->path($value));
        });

        Validator::extend('importable_url', function (string $attribute, string $value) {
            return resolve(GallerydlWrapper::class)->setUrl($value)->extractorInfo()->run()->isSuccessful();
        });
    }
}
