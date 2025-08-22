<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class IsSameHour extends BaseMacro
{
    public static function name(): string
    {
        return 'selectIsSameHour';
    }

    public function defaultExpression($column1, $column2): string
    {
        return "DATE_TRUNC('hour', $column1) = DATE_TRUNC('hour', $column2)";
    }

    public function oracle($column1, $column2): string
    {
        return "CASE WHEN TRUNC($column1, 'HH24') = TRUNC($column2, 'HH24') THEN 1 ELSE 0 END";
    }

    public function sqlite($column1, $column2): string
    {
        return "STRFTIME('%Y-%m-%d %H', $column1) = STRFTIME('%Y-%m-%d %H', $column2)";
    }

    public function mysql($column1, $column2): string
    {
        return "HOUR($column1) = HOUR($column2)";
    }
}