<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Compartir usuario autenticado con todas las vistas
        View::composer('*', function ($view) {
            $view->with('auth', [
                'user' => auth()->user()
            ]);
        });
    }
}