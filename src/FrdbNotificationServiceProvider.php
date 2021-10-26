<?php

namespace Virtunus\FrdbNotification;

use Illuminate\Support\ServiceProvider;

class FrdbNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->rootPath = realpath(__DIR__ . '/../');

        $this->mergeConfigFrom($this->rootPath . '/config/firebase-channel.php', 'firebase-channel');
    }
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }
}
