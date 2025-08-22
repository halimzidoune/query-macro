<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectSubstring
 * Purpose: Extract a substring from a string column using 0-based start index (converted internally per driver).
 * Example:
 *   - Given: designation = "Manager"
 *   - Usage: ->selectSubstring('designation as firstTwo', 0, 2)
 *   - Result: firstTwo = "Ma"
 */
class Substring extends BaseMacro
{
    public static function name(): string {
        return 'selectSubstring';
    }

    private function normalizeStart($start)
    {
        if (is_numeric($start)) {
            $start = (int) $start;
            if ($start >= 0) {
                return $start + 1; // Convert 0-based to 1-based for SQL
            }
        }
        return $start; // Leave negative or non-numeric as-is
    }

    public function defaultExpression($column, $start, $length = null): string {
        $start = $this->normalizeStart($start);
        $length = $length ? ", $length" : '';
        return "SUBSTRING($column, $start$length)";
    }

    public function mysql($column, $start, $length = null): string {
        return $this->defaultExpression($column, $start, $length);
    }

    public function pgsql($column, $start, $length = null): string {
        $start = $this->normalizeStart($start);
        return $length ? "SUBSTR($column, $start, $length)" : "SUBSTR($column, $start)";
    }

    public function sqlsrv($column, $start, $length = null): string {
        $start = $this->normalizeStart($start);
        return $length ? "SUBSTRING($column, $start, $length)" : "SUBSTRING($column, $start, LEN($column))";
    }

    public function oracle($column, $start, $length = null): string {
        $start = $this->normalizeStart($start);
        return $length ? "SUBSTR($column, $start, $length)" : "SUBSTR($column, $start)";
    }
}