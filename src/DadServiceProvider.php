<?php

namespace edgewizz\dad;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class DadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Edgewizz\Dad\Controllers\DadController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // dd($this);
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__ . '/components', 'dad');
        Blade::component('dad::dad.open', 'dad.open');
        Blade::component('dad::dad.index', 'dad.index');
        Blade::component('dad::dad.edit', 'dad.edit');
    }
}
