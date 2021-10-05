<?php

namespace TelescopePlus\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait Traceable {

    private array $highlights;

        /**
     * html returns a formatted backtrace
     *
     * @param integer $limit
     * @return string
     */
    public function formatBacktrace(): string
    {
        try {
            $backTrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, config('telescopeplus.query.limit', 30));
            $result = collect($backTrace)->map(function ($item) {
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

                if (!$this->shouldDisplay($line)) {
                    // Does not output Telescope method calls
                    return;
                }

                return $this->getHtmlLine($line);
            })->join('<br>');

            return $result;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function shouldDisplay(string $line): bool
    {
        if (Str::startsWith($line, 'Laravel\\Telescope\\') || Str::startsWith($line, 'TelescopePlus\\Watchers\\')) {
            return false;
        } else {
            return true;
        }
    }

    public function getHtmlLine(string $line): string
    {
        foreach ($this->highlights as $item) {
            if (Str::contains($line, $item['target'])) {
                $color = $item['color'];
                return "<span style=\"font-weight: bold;color:${color}\">${line}</span>";
            }
        }

        return $line;
    }
}