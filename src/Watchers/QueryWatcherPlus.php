<?php

namespace TelescopePlus\Watchers;

use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\Watchers\QueryWatcher;
use TelescopePlus\Traits\Traceable;

class QueryWatcherPlus extends QueryWatcher
{
    use Traceable;

    public function __construct() 
    {
        $this->highlights = config('telescopeplus.query.highlight', []);
    }

    /**
     * Record a query was executed.
     *
     * @param  \Illuminate\Database\Events\QueryExecuted  $event
     * @return void
     */
    public function recordQuery(QueryExecuted $event)
    {
        if (!Telescope::isRecording()) {
            return;
        }

        $time = $event->time;

        if ($caller = $this->getCallerFromStackTrace()) {
            Telescope::recordQuery(IncomingEntry::make([
                'connection' => $event->connectionName,
                'bindings' => [],
                'sql' => $this->replaceBindings($event),
                'time' => number_format($time, 2, '.', ''),
                'slow' => isset($this->options['slow']) && $time >= $this->options['slow'],
                'file' => $this->formatBacktrace(),
                'line' => $caller['line'],
                'hash' => $this->familyHash($event),
            ])->tags($this->tags($event)));
        }
    }
}
