<?php

namespace TelescopePlus\Watchers;

use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;
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
     * html整形したバックトレースを返す
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
                    // クラス名を含む場合、クラス名を出力する
                    if (array_key_exists('line', $item)) {
                        // 行番号を含む場合、行番号を出力する
                        $line = $item['class'] . ":" . $item['line'] . " " . $item['function'];
                    } else {
                        // 行番号を含まない場合（クロージャの場合）、行番号を出力しない
                        $line = $item['class'] . " " . $item['function'];
                    }
                } else {
                    // クラス名を含まない場合、ファイル名を出力する
                    $line = $item['file'] . ":" . $item['line'] . " " . $item['function'];
                }

                if(str_starts_with($line, 'Laravel\\Telescope\\')) {
                    // Telescopeのメソッド呼び出しは出力しない
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
            if(strpos($line, $item['target']) !== false) {
                $color = $item['color'];
                return "<span style=\"font-weight: bold;color:${color}\">${line}</span>";
            }
        }

        return $line;
    }
}