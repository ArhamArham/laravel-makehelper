<?php

namespace Arham\MakeHelper;

use Illuminate\Support\ServiceProvider;

class MakeHelperServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/makehelper.php' => config_path('makehelper.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                HelperCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/makehelper.php',
            'makehelper'
        );
    }
}
