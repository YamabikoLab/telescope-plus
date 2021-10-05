<?php

namespace TelescopePlus\Tests\Watchers;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase;
use phpmock\MockBuilder;
use TelescopePlus\Watchers\QueryWatcherPlus;

class QueryWatcherPlusTest extends TestCase
{
    public function test_formatBacktrace_When_all_the_test_data_of_the_normal_system_exists_Should_be_output_according_to_the_specifications() {
        $data = json_decode(File::get(__DIR__ . '/../data/test.json'), true);
        $builder = new MockBuilder();
        $builder->setNamespace('TelescopePlus\\Traits')
                ->setName("debug_backtrace")
                ->setFunction(
                    function() use ($data) {
                        return $data;
                    }
                )
                ->build()
                ->enable();
        
        $queryWatcherPlus = new QueryWatcherPlus();
        $result = $queryWatcherPlus->formatBacktrace();
        $expect = "<br><span style=\"font-weight: bold;color:red\">Illuminate\Events\Dispatcher:878 dispatch</span><br><span style=\"font-weight: bold;color:blue\">Illuminate\Session\DatabaseSessionHandler read</span><br>/var/www/html/vendor/laravel/framework/src/Illuminate/Routing/Router.php:697 then<br>Illuminate\Pipeline\Pipeline:103 Illuminate\Pipeline\{closure}";
        $this->assertEquals($expect, $result);
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $config = $app->get('config');
        $config->set('telescopeplus.query.limit', 4);
        $config->set('telescopeplus.query.highlight',[
            [
                'target' => 'Illuminate\\Events',
                'color' => 'red'
            ],
            [
                'target' => '\\Session\\',
                'color' => 'blue'
            ],
        ]);
                        
    }
}