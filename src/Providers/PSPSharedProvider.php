<?php

namespace IMPelevin\PSPShared\Providers;

use Illuminate\Support\ServiceProvider;
use IMPelevin\PSPShared\Commands\FindAndAddLanguageKeys;

class PSPSharedProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    public function registerPublishing()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../LTree/Migrations' => database_path('migrations'),
            ], 'psp-ltree-migrations');

            $this->commands([
                FindAndAddLanguageKeys::class,
            ]);

        }
    }
}
