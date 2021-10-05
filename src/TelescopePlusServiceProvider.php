<?php

namespace TelescopePlus;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
        $this->registerCommands();
        $this->registerPublishing();
    }

        /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/telescopeplus.php' => config_path('telescopeplus.php'),
            ], 'telescope-plus-config');

            if(File::exists(public_path('vendor/telescope/app.js'))) {
                $this->overwriteAppjs(public_path('vendor/telescope/app.js'), __DIR__.'/../public/app.js');
            } else {
                $this->overwriteAppjs(base_path('vendor/laravel/telescope/public/app.js'), __DIR__.'/../public/app.js');
            }
            

            $this->publishes([
                __DIR__.'/../public/app.js' => public_path('vendor/telescope/app.js'),
            ], 'telescope-plus-assets');
        }
    }

    private function overwriteAppjs($input, $output) {
        $content = File::get($input);
        $replaced = Str::replaceLast("[e._v(\"\\n                \"+e._s(t.entry.content.file)+\":\"+e._s(t.entry.content.line)+\"\\n            \")])])", "{domProps:{innerHTML:e._s(t.entry.content.file)}})])", $content);
        File::put($output, $replaced);
    }
}
