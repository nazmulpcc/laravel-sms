<?php

namespace Nazmulpcc\LaravelSms\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Nazmulpcc\LaravelSms\Contracts\MustVerifyPhone;
use Nazmulpcc\LaravelSms\Facades\LaravelSms as LaravelSmsFacade;
use Nazmulpcc\LaravelSms\LaravelSms;

class LaravelSmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/laravel-sms.php' => config_path('laravel-sms.php')
        ]);

        Event::listen(Registered::class, function(Registered $event){
            $event->user instanceof MustVerifyPhone &&
            $event->user->sendPhoneVerificationNotification();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias(LaravelSms::class, LaravelSmsFacade::class);
        $this->app->alias(LaravelSms::class, 'laravel-sms');
        $this->app->singleton(LaravelSms::class, function(){
            return new LaravelSms(config('laravel-sms.driver'));
        });

//        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
    }
}
