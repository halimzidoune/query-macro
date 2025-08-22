<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectEndsWith
 * Purpose: Check if a string ends with a given suffix. Returns 1/0.
 * Example:
 *   - Given: designation = "Dev"
 *   - Usage: ->selectEndsWith('designation as ew', 'ev')
 *   - Result: ew = 1
 */
class EndsWith extends BaseMacro
{
    public static function name(): string
    {
        return 'selectEndsWith';
    }
    public function defaultExpression($column, $value): string
    {
        return "$column LIKE '%$value'";
    }

    public function mysql($column, $value): string
    {
        return "RIGHT($column, " . strlen($value) . ") = '$value'";
    }

    public function pgsql($column, $value): string
    {
        return "$column LIKE '%$value'";
    }

    public function sqlsrv($column, $value): string
    {
        return "$column LIKE '%$value'";
    }

    public function oracle($column, $value): string
    {
        return "REGEXP_LIKE($column, '$value$') OR " .
            "(SUBSTR($column, -" . strlen($value) . ") = '$value' AND $column IS NOT NULL)";
    }

    public function sqlite($column, $value): string
    {
        return "$column GLOB '*$value'";
    }
}