<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Casts;


use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectInteger
 * Purpose: Cast a value to integer.
 * Example:
 *   - Given: designation = "1"
 *   - Usage: ->selectInteger('designation as i')
 *   - Result: i = 1
 */
class SelectInteger extends BaseMacro
{
    public static function name(): string
    {
        return 'selectInteger';
    }

    public function defaultExpression($column): string
    {
        return "CAST($column AS INTEGER)";
    }

    public function mysql($column): string
    {
        return "CAST($column AS SIGNED INTEGER)";
    }

    public function pgsql($column): string
    {
        return "CAST($column AS INTEGER)";
    }

    public function sqlsrv($column): string
    {
        return "CAST($column AS INT)";
    }

    public function oracle($column): string
    {
        return "CAST($column AS NUMBER(10))";
    }

    public function sqlite($column): string
    {
        return "CAST($column AS INTEGER)";
    }
}