<?php

namespace Slexx\LaravelBladeEvents;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class EventsServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {

    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        Blade::directive('event', function ($expression) {
            return "<?php \Slexx\LaravelBladeEvents\EventManager::fire({$expression}); ?>";
        });
    }
}
