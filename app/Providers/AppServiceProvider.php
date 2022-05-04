<?php

namespace App\Providers;

use App\ImageKit\ImagekitAdapter;
use Hotash\Authable\AuthGuard;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ImageKit\ImageKit;
use League\Flysystem\Filesystem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('imagekit', function ($app, $config) {
            // Get client
            $client = new ImageKit (
                config('imagekit.public'),
                config('imagekit.private'),
                config('imagekit.endpoint')
            );

            // Get adapter
            $adapter = new ImagekitAdapter($client, $config['root']);

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }
}
