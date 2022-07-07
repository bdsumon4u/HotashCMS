<?php

namespace App\Providers;

use App\Database\Schema\BlueprintMixin;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    protected array $mixins = [
        Blueprint::class => BlueprintMixin::class,
    ];

    protected array $testingMixins = [
        //
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMixins($this->mixins);

        if ($this->app->environment('testing')) {
            $this->registerMixins($this->testingMixins);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function registerMixins($mixins)
    {
        foreach ($mixins as $class => $mixin) {
            if (! is_array($mixin)) {
                $mixin = [$mixin];
            }

            foreach ($mixin as $item) {
                $class::mixin(new $item);
            }
        }
    }
}
