<?php

namespace TelescopePlus\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telescope-plus:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Telescope Plus resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Telescope Plus Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'telescope-plus-assets']);

        $this->comment('Publishing Telescope Plus Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'telescope-plus-config']);

        $this->info('Telescope Plus installed successfully.');
    }
}