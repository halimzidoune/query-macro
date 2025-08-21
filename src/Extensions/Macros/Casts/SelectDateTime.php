<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Casts;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectCastToDateTime - Converts values to datetime type
 * Example: CAST('2023-01-01 12:00:00' AS DATETIME)
 */
class SelectDateTime extends BaseMacro
{
    public static function name(): string
    {
        return 'selectDateTime';
    }

    public function mysql($column): string
    {
        return $this->defaultExpression($column);
    }

    public function defaultExpression($column): string
    {
        return "CAST($column AS DATETIME)";
    }

    public function pgsql($column): string
    {
        return "CAST($column AS TIMESTAMP)";
    }

    public function sqlsrv($column): string
    {
        return "CONVERT(DATETIME, $column)";
    }

    public function oracle($column): string
    {
        return "TO_TIMESTAMP($column, 'YYYY-MM-DD HH24:MI:SS')";
    }

    public function sqlite($column): string
    {
        return "DATETIME($column)";
    }
}