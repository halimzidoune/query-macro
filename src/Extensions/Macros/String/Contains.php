<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectContains
 * Purpose: Check if a string contains a given substring. Returns 1/0.
 * Example:
 *   - Given: designation = "Manager"
 *   - Usage: ->selectContains('designation as c', 'nag')
 *   - Result: c = 1
 */
class Contains extends BaseMacro
{
    public static function name(): string
    {
        return 'selectContains';
    }

    public function defaultExpression($column, $value): string
    {
        return "$column LIKE '%$value%'";
    }

    public function mysql($column, $value): string
    {
        return "LOCATE('$value', $column) > 0";
    }

    public function pgsql($column, $value): string
    {
        return "POSITION('$value' IN $column) > 0";
    }

    public function sqlsrv($column, $value): string
    {
        return "CHARINDEX('$value', $column) > 0";
    }
}