<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class DaysInMonth extends BaseMacro
{
    public static function name(): string
    {
        return 'selectDaysInMonth';
    }

    public function defaultExpression($column): string
    {
        return "EXTRACT(DAY FROM (DATE_TRUNC('month', $column) + INTERVAL '1 month' - INTERVAL '1 day'))";
    }

    public function mysql($column): string
    {
        return "DAY(LAST_DAY($column))";
    }
    
    public function oracle($column): string
    {
        return "EXTRACT(DAY FROM LAST_DAY($column))";
    }

    public function sqlite($column): string
    {
        return "CAST(STRFTIME('%d', DATE($column, 'start of month', '+1 month', '-1 day')) AS INTEGER)";
    }

    public function pgsql($column): string
    {
        return "FLOOR(EXTRACT(DAY FROM (DATE_TRUNC('month', $column) + INTERVAL '1 month' - INTERVAL '1 day')))::integer";
    }
}