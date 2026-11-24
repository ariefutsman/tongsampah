<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', fn (User $user) => $user->isAdmin());
        Gate::define('petugas', fn (User $user) => $user->isPetugas());

        // The admin template's CSS (public/assets/css/main.css) targets Bootstrap 5's
        // native pagination markup (ul.pagination > li.page-item > a.page-link) — Laravel's
        // default Tailwind pagination view has no matching styles, so it renders unstyled.
        Paginator::useBootstrapFive();

        // This app has no route named "password.reset" (Laravel's convention) — routes
        // are namespaced under "admin.*" — so point the reset email link there instead.
        ResetPassword::createUrlUsing(function (User $notifiable, string $token) {
            return route('admin.password.reset', [
                'token' => $token,
                'email' => $notifiable->email,
            ]);
        });
    }
}
