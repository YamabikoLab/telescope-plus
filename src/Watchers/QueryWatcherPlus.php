<?php

namespace TelescopePlus\Watchers;

use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\Watchers\QueryWatcher;

class QueryWatcherPlus extends QueryWatcher
{
    /**
     * Record a query was executed.
     *
     * @param  \Illuminate\Database\Events\QueryExecuted  $event
     * @return void
     */
    public function recordQuery(QueryExecuted $event)
    {
        if (! Telescope::isRecording()) {
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

    /**
     * html returns a formatted backtrace
     *
     * @param integer $limit
     * @return string
     */
    protected function formatBacktrace():string
    {
        try {
            $backTrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, config('telescopeplus.query.limit', 30));
            $highlights = config('telescopeplus.query.highlight', []);
            $result = collect($backTrace)->map(function($item) use($highlights) {
                if (array_key_exists('class', $item)) {
                    // If the class name is included, the class name is output.
                    if (array_key_exists('line', $item)) {
                        // If the line number is included, the line number is output.
                        $line = $item['class'] . ":" . $item['line'] . " " . $item['function'];
                    } else {
                        // If the line number is not included (in the case of closure), the line number is not output.
                        $line = $item['class'] . " " . $item['function'];
                    }
                } else {
                    // If the class name is not included, the file name is output.
                    $line = $item['file'] . ":" . $item['line'] . " " . $item['function'];
                }

                if(Str::startsWith($line, 'Laravel\\Telescope\\')) {
                    // Does not output Telescope method calls
                    return;
                }

                return $this->getHtmlLine($line, $highlights);
            })->join('<br>');

             return $result;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function getHtmlLine($line, $highlights) {
        foreach($highlights as $item) {
            if(Str::contains($line, $item['target'])) {
                $color = $item['color'];
                return "<span style=\"font-weight: bold;color:${color}\">${line}</span>";
            }
        }

        return $line;
    }
}