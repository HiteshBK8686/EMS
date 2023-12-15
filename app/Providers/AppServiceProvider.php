<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
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
        //
       // Password reset link in email template...
    // ResetPassword::createUrlUsing(static function ($notifiable, $token) {
    //     // Url of the fronted app for resetting password...
    //     return config('app_url').'/reset-password/'.$token;
     //});

      
       
    }

    protected function setFrontEndUrlInResetPasswordEmail($frontEndUrl = '')
    {
        // update url in ResetPassword Email to frontend url
        ResetPassword::createUrlUsing(function ($user, string $token) use ($frontEndUrl) {
            return env('APP_URL').'/password/email/reset?token=' . $token;
        });
    }

}
