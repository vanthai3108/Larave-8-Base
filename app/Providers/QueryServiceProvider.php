<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
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
        Builder::macro('andWhen', function ($attributes) {
            return $this->where(function (Builder $query) use ($attributes) {
                foreach (Arr::wrap($attributes) as $key => $value) {
                    $query->when($key, function (Builder $query) use ($key, $value) {
                        $query->where($key, $value);
                    });
                }
            });
        });
    }
}
