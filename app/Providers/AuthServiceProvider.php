<?php

namespace App\Providers;

 use Illuminate\Support\Facades\Gate;
 use Illuminate\Auth\Notifications\VerifyEmail;
 use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
     
    }


    protected function setFrontEndUrlInResetPasswordEmail($frontEndUrl = '')
    {
        // update url in ResetPassword Email to frontend url
        ResetPassword::createUrlUsing(function ($user, string $token) use ($frontEndUrl) {
            return $frontEndUrl . '/auth/password/email/reset?token=' . $token;
        });
    }
}
