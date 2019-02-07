# laravel-blade-events [English](https://github.com/slexx1234/laravel-blade-events/blob/master/README.md)

## Использование

### Базовое использование

Этот пакет предлагает событейную модель для Blade шаблонизатора, добовляя одну
директиву `@event`.

index.blade.php:
```blade 
@event('unique-event-name');
```

Слушать события мы можем добавив следующие строки в файл `app/Provides/EventServiceProvider.php` в метод `boot`:

```php
use Slexx\LaravelBladeEvents\EventManager;

// ...

EventManager::listen('unique-event-name', function($event) {
    echo '<h1>Привет из подполья!</h1>';
});
```

Вывод будет такой:

```html 
<h1>Привет из подполья!</h1>
```

### Прерывание очериди событий

Можно использовать несколько слушателей и на каком то моменте прервать другие:

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

Выведет:

```html 
12
```

### Аргументы

С помощью директивы `@event` в слушатель события можно передавать неограниченое число аргументов,
получить к ним доступ можно с помощью метода `getArguments` класса `Event`:

```blade 
@event('unique-event-name', 'listener-first-argument', 'listener-two-argument');
```
```php
EventManager::listen('unique-event-name', function($event) {
    dd($event->getArguments());
});
```

### Приоритет 

Для слушателей событий можно указывать приоритет, что бы слушатель сработал в нужный момент:

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

### Слушатели событий

В качестве слушателя события можно указать имя функции:

```
EventManager::listen('some-event', 'someEventListener');
```

Или даже класс:

```
EventManager::listen('some-event', '\Foo\Bar\SomeEventListener@handle');
// Или так
EventManager::listen('some-event', ['\Foo\Bar\SomeEventListener', 'handle']);
```

### Имена событий

Можно слушать сразу несколько событий, разделив их имена символом "|":

```
EventManager::listen('package-one::some-event|package-two::some-event', '\Foo\Bar\SomeEventListener@handle');
```

Или передав список имён массивом:

```
EventManager::listen(['package-one::some-event', 'package-two::some-event'], '\Foo\Bar\SomeEventListener@handle');
```

### Соглашение по именованию

Для каждого пакета имя события должно сотоять из имени пакета и имени 
события разделёнными двойным двоеточием `имя пакета::имя события`.

## Установка 

Установка через composer:

```
composer require slexx/laravel-blade-events
```

После обновления composer, добавте поставщика услуг в массив `providers` в файлу `config/app.php`

```
Slexx\LaravelBladeEvents\EventsServiceProvider::class,
```
