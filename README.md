# Telescope Plus
# Introduction
Telescope Plus is an extension of Laravel Telescope.
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
    'query'=>[
        'limit' => 30,
        'highlight' => [
            [
                'target' => 'App\\',
                'color' => 'red'
            ],
            [
                'target' => 'Illuminate\\Database\\',
                'color' => 'gold'
            ],
            [
                'target' => '\\Middleware\\',
                'color' => 'green'
            ],
        ],
    ],
```