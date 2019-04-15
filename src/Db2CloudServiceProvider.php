<?php

namespace Db2Cloud;

use Illuminate\Support\ServiceProvider;
use Db2Cloud\Db2Cloud;
use Db2Cloud\Commands\Db2CloudCommand;

class Db2CloudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        $this->publishes([
            __DIR__.'/config/backupconfig.php' => config_path('backupconfig.php')
        ], 'backupconfig');
    }


    /**
     * Register the application services.
     */
    public function register()
    {

        $this->app->bind('command.db:backup', Db2CloudCommand::class);
        $this->commands([
            'command.db:backup'
        ]);

        $this->app->singleton('Db2Cloud',function(){
            return new Db2Cloud();
        });

    }
}
