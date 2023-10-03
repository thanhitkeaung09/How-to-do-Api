<?php

namespace App\Providers;

use App\Services\DigitalOcean\CdnService;
use App\Services\FileStorage\DOCdnService;
use App\Services\FileStorage\FileStorageService;
use App\Services\FileStorage\SpaceStorage;
use Illuminate\Support\ServiceProvider;

class DigitalOceanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(FileStorageService::class, SpaceStorage::class);
    }
}
