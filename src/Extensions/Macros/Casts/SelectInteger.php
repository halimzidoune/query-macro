<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Casts;


use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectCastToInt - Converts values to integer type
 * Example: CAST('123' AS INTEGER)
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