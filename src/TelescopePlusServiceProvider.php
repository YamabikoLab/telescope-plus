<?php

namespace TelescopePlus;

use Illuminate\Support\ServiceProvider;

class TelescopePlusServiceProvider extends ServiceProvider
{
    /**
     * 全パッケージサービスの初期起動処理
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/telescopeplus.php' => config_path('telescopeplus.php'),
        ]);
    }
}
