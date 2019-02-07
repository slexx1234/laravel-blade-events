<?php

namespace Slexx\LaravelBladeEvents;

class EventManager
{
    protected static $listeners = [];

    /**
     * @param string|array $events
     * @param callable|string $callback
     * @param int [$priority]
     * @example:
     * <?php
     *
     * namespace App\Providers;
     *
     * use Illuminate\Support\ServiceProvider;
     * use Slexx\LaravelBladeEvents\EventManager;
     *
     * class BladeEventsServiceProvider extends ServiceProvider
     * {
     *     public function register()
     *     {
     *
     *     }
     *
     *     public function boot()
     *     {
     *         EventManager::listen('package-one::footer|package-two::footer', function() {
     *             echo view('includes.footer');
     *         });
     *
     *         EventManager::listen(['package-one::head', 'package-two::head'], 'App/Events/BladeHeadEventListener@handle');
     *     }
     * }
     */
    public static function listen($events, $callback, $priority = 0)
    {
        if (is_string($events)) {
            $events = explode('|', $events);
        }

        foreach($events as $event) {
            if (!isset(self::$listeners[$event])) {
                self::$listeners[$event] = [];
            }

            self::$listeners[$event][] = [
                'callback' => $callback,
                'priority' => $priority,
            ];
        }
    }

    /**
     * @param Event $event
     * @return array
     */
    protected static function getListenersForEvent($event)
    {
        if (!isset(self::$listeners[$event->getName()])) {
            return [];
        }

        $result = self::$listeners[$event->getName()];


        // Сортирую обработчики события по приоритету
        uasort($result, function($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }
            return ($a['priority'] < $b['priority']) ? -1 : 1;
        });

        return $result;
    }

    /**
     * @param Event $event
     * @param callable|string $callback
     */
    protected static function executeCallback($event, $callback) {
        if (is_string($callback) && mb_strpos($callback, '@') !== false) {
            call_user_func(explode('@', $callback), $event);
        } else {
            call_user_func($callback, $event);
        }
    }

    /**
     * @param string $name
     * @param mixed ...$arguments
     */
    public static function fire($name, ...$arguments)
    {
        $event = new Event($name, $arguments);

        foreach(self::getListenersForEvent($event) as $data) {
            if ($event->isStopped()) {
                break;
            }

            self::executeCallback($event, $data['callback']);
        }
    }
}
