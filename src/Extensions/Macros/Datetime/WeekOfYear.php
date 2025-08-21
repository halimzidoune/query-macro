<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class WeekOfYear extends BaseMacro
{
    public static function name(): string
    {
        return 'selectWeekOfYear';
    }

    public function defaultExpression($column): string
    {
        return "EXTRACT(WEEK FROM $column)";
    }

    public function mysql($column): string
    {
        return "WEEK($column, 3)"; // ISO week
    }

    public function sqlsrv($column): string
    {
        return "DATEPART(ISO_WEEK, $column)";
    }

    public function sqlite($column): string
    {
        return "CAST(STRFTIME('%W', $column) AS INTEGER)";
    }
}