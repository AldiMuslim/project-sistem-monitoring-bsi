<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

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
        if (str_contains(request()->getHttpHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }

        // Gate untuk mengecek apakah user adalah admin
        Gate::define('manage-users', function (User $user) {
            return $user->jabatan === 'admin';
        });

        // Gate untuk mengecek apakah user adalah petugas
        Gate::define('is-petugas', function (User $user) {
            return $user->jabatan === 'petugas';
        });

        // Interupsi Email Reset Password bawaan agar memakai desain BSI Premium
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Keamanan Akun - BSI Inventory System')
                ->view('emails.bsi-reset-password', ['url' => $url, 'name' => $notifiable->name]);
        });
    }
}
