# laravel-blade-events [Русский](https://github.com/slexx1234/laravel-blade-events/blob/master/RU.md)

## Usage

### Basic usage

This package offers an event model for Blade template engine, adding one
directive `@ event`.

index.blade.php:
```blade 
@event('unique-event-name');
```

We can add the following lines to the `app/Provides/EventServiceProvider.php` file in the` boot` method for listen events:

```php
use Slexx\LaravelBladeEvents\EventManager;

// ...

EventManager::listen('unique-event-name', function($event) {
    echo '<h1>Привет из подполья!</h1>';
});
```

Result will be:

```html 
<h1>Привет из подполья!</h1>
```

### Break point

You can use several listeners and interrupt others at some point:

```php
use Slexx\LaravelBladeEvents\EventManager;

// ...

EventManager::listen('unique-event-name', function($event) {
    echo 1;
});
EventManager::listen('unique-event-name', function($event) {
    $event->stop();
    echo 2;
});
EventManager::listen('unique-event-name', function($event) {
    echo 3;
});
```

Result:

```html 
12
```

### Arguments

With the help of the `@event` directive, an unlimited number of arguments can be passed to the event listener,
they can be accessed using the `getArguments` method of the `Event` class:

```blade 
@event('unique-event-name', 'listener-first-argument', 'listener-two-argument');
```
```php
EventManager::listen('unique-event-name', function($event) {
    dd($event->getArguments());
});
```

### Priority 

Event listeners can have specify priority:

```php
EventManager::listen('unique-event-name', function($event) {
    echo 1;
});
EventManager::listen('unique-event-name', function($event) {
    $event->stop();
    echo 2;
});
EventManager::listen('unique-event-name', function($event) {
    echo 3;
});

// ...

// Disable all event listeners
EventManager::listen('unique-event-name', function($event) {
    $event->stop();
}, -100);
```

### Event listeners

As an event listener, you can specify the name of the function:

```
EventManager::listen('some-event', 'someEventListener');
```

Or class name:

```
EventManager::listen('some-event', '\Foo\Bar\SomeEventListener@handle');
// Или так
EventManager::listen('some-event', ['\Foo\Bar\SomeEventListener', 'handle']);
```

### Event names

You can listen to several events at once by separating their names with the symbol "|":

```
EventManager::listen('package-one::some-event|package-two::some-event', '\Foo\Bar\SomeEventListener@handle');
```

Or passed the list of names as an array:

```
EventManager::listen(['package-one::some-event', 'package-two::some-event'], '\Foo\Bar\SomeEventListener@handle');
```

## Install 

You can install this package via composer:

```
composer require slexx/laravel-blade-events
```

After updating composer, add the service provider to the `providers` array in `config/app.php`

```
Slexx\LaravelBladeEvents\EventsServiceProvider::class,
```
