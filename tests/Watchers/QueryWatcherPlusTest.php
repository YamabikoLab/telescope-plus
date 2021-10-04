<?php

namespace TelescopePlus\Tests\Watchers;

use ReflectionClass;
use TelescopePlus\Tests\FeatureTestCase;
use TelescopePlus\Watchers\QueryWatcherPlus;

class QueryWatcherPlusTest extends FeatureTestCase
{
    public function testHoge() {
        $queryWatcherPlus = new QueryWatcherPlus();
        $reflection = new ReflectionClass($queryWatcherPlus);
        $method = $reflection->getMethod('formatBacktrace');
        $method->setAccessible(true);
        $result = $method->invokeArgs($queryWatcherPlus, []);
        $this->assertNotNull($result);
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $config = $app->get('config');
        $config->set('telescopeplus.query.limit', 30);
        $config->set('telescopeplus.query.highlight',[
            [
                'target' => 'TelescopePlus\\Watchers',
                'color' => 'red'
            ],
            [
                'target' => '\\Framework\\',
                'color' => 'blue'
            ],
        ]);
                        
    }
}