<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Casts;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectCastToDate - Converts values to date type
 * Example: CAST('2023-01-01' AS DATE)
 */
class SelectDate extends BaseMacro
{
    public static function name(): string
    {
        return 'selectDate';
    }

    public function mysql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function defaultExpression($column): string
    {
        return "CAST($column AS DATE)";
    }

    public function pgsql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function sqlsrv($column): string
    {
        return "CONVERT(DATE, $column)";
    }

    public function oracle($column): string
    {
        return "TO_DATE($column, 'YYYY-MM-DD')";
    }

    public function sqlite($column): string
    {
        return "DATE($column)";
    }
}