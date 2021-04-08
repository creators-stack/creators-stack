<?php

namespace App\Providers;

use App\Models\Creator;
use App\Models\File;
use App\Observers\CreatorObserver;
use App\Observers\FileObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Creator::observe(CreatorObserver::class);
        File::observe(FileObserver::class);
    }
}
