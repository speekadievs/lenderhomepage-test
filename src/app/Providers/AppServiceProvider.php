<?php

namespace App\Providers;

use App\Models\Film;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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
        Relation::morphMap([
            'films' => Film::class,
        ]);

        Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
            return auth()->check() && Hash::check($value, auth()->user()->password);
        });

    }
}
