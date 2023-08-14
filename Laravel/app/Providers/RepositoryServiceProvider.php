<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $files = scandir(app_path('Repository'));
        foreach ($files as $file) {
            if (Str::endsWith($file, 'Interface.php')) {
                $abstract = 'App\Repository\\' . Str::replaceFirst('.php', '', $file);
                $conCreate = 'App\Repository\Eloquent\\' . Str::replaceFirst('Interface.php', '', $file);
                $this->app->bind($abstract, $conCreate);
            }
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
