<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        date_default_timezone_set(config('app.timezone'));
        Relation::morphMap($this->getMorphMap());
    }

    private function getMorphMap()
    {
        $maps = [];
        $files = scandir(app_path('Models'));
        foreach ($files as $file) {
            if (Str::endsWith($file, '.php')) {
                $model = 'App\Models\\' . Str::replaceFirst('.php', '', $file);
                $table = call_user_func(array(new $model(), 'getTable'));
                $maps[$table] = $model;
            }
        }
        return $maps;
    }
}
