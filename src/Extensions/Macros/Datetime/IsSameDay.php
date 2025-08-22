<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class IsSameDay extends BaseMacro
{
    public static function name(): string
    {
        return 'selectIsSameDay';
    }

    public function defaultExpression($column1, $column2): string
    {
        return "DATE_TRUNC('day', $column1) = DATE_TRUNC('day', $column2)";
    }

    public function mysql($column1, $column2): string
    {
        return "DATE($column1) = DATE($column2)";
    }

    public function pgsql($column1, $column2): string
    {
        return "DATE_TRUNC('day', $column1) = DATE_TRUNC('day', $column2)";
    }

    public function sqlsrv($column1, $column2): string
    {
        return "CAST($column1 AS DATE) = CAST($column2 AS DATE)";
    }

    public function oracle($column1, $column2): string
    {
        return "CASE WHEN TRUNC($column1) = TRUNC($column2) THEN 1 ELSE 0 END";
    }

    public function sqlite($column1, $column2): string
    {
        return "DATE($column1) = DATE($column2)";
    }
}