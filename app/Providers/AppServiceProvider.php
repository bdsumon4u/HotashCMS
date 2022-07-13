<?php

namespace App\Providers;

use App\ImageKit\ImagekitAdapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
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
        Model::preventLazyLoading(!app()->isProduction());

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
