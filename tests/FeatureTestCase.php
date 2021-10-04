<?php

namespace TelescopePlus\Tests;

use Orchestra\Testbench\TestCase;
use TelescopePlus\TelescopePlusServiceProvider;

class FeatureTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TelescopePlusServiceProvider::class,
        ];
    }
}