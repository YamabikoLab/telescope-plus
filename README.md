# Telescope Plus
# Introduction
Telescope Plus is an extension of Laravel Telescope.

## Query Watcher Plus
![](https://user-images.githubusercontent.com/88073693/135932316-3a737ac9-8123-406a-b840-da9acec52130.png)

![](https://user-images.githubusercontent.com/88073693/135932638-2364f010-3696-40f5-9e93-e38e2026fbe3.jpg)
)
# Installation
```bash
composer require yamabiko/teltescope-plus --dev

php artisan telescope-plus:install
```
# Configuration
## Query Watcher
config/telescope.php
```diff php
+ TelescopePlus\Watchers\QueryWatcherPlus::class => [
- Watchers\QueryWatcher::class => [
```

config/telescopeplus.php
```php
    /*
    |--------------------------------------------------------------------------
    | Telescope Query Watcher config
    |--------------------------------------------------------------------------
    | Limit parameter can be used to limit the number of stack frames returned. 
    | You can specify the target and color using the highlight parameters.
    */
    'query'=>[
        'limit' => 30,
        'highlight' => [
            [
                'target' => 'App\\',
                'color' => 'red'
            ],
            [
                'target' => 'Illuminate\\Database\\',
                'color' => 'blue'
            ],
            [
                'target' => '\\Middleware\\',
                'color' => 'green'
            ],
        ],
    ],
```