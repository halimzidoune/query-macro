<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Extensions\Macros\String\SelectCase;

class DayOfWeek extends BaseMacro
{
    public static function name(): string
    {
        return 'selectDayOfWeek';
    }

    public function defaultExpression($column): string
    {
        return "EXTRACT(DOW FROM $column) + 1";
    }

    public function mysql($column): string
    {
        return "DAYOFWEEK($column)";
    }

    public function pgsql($column): string
    {
        return "EXTRACT(DOW FROM $column) + 1";
    }

    public function sqlsrv($column): string
    {
        return "DATEPART(WEEKDAY, $column)";
    }

    public function oracle($column): string
    {
        return "TO_CHAR($column, 'D')";
    }

    public function sqlite($column, $map = null): string
    {
        $result = "strftime('%w', $column) + 1";
        if($map){
            $result = SelectCase::make()->getExpression($this->driver, $result, $map);
        }
        return $result;
    }
}