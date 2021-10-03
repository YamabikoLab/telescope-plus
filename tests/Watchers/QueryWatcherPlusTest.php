<?php

namespace TelescopePlus\Tests\Watchers;

use PHPUnit\Framework\TestCase;
use TelescopePlus\Watchers\QueryWatcherPlus;

class QueryWatcherPlusTest extends TestCase
{
    public function testHoge() {
        $queryWatcherPllus = new QueryWatcherPlus();
        $result = $queryWatcherPllus->formatBacktrace();
        $this->assertNotNull($result);
    }
}