<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class IsSameYear extends BaseMacro
{
    public static function name(): string { return 'selectIsSameYear'; }

    public function defaultExpression($column1, $column2): string
    {
        return "EXTRACT(YEAR FROM $column1) = EXTRACT(YEAR FROM $column2)";
    }

    public function mysql($column1, $column2): string
    {
        return "YEAR($column1) = YEAR($column2)";
    }

    public function sqlite($column1, $column2): string
    {
        return "STRFTIME('%Y-%m-%d %H', $column1) = STRFTIME('%Y-%m-%d %H', $column2)";
    }

    public function oracle($column1, $column2): string
    {
        return "CASE WHEN EXTRACT(YEAR FROM $column1) = EXTRACT(YEAR FROM $column2) THEN 1 ELSE 0 END";
    }

    public function sqlsrv($column1, $column2): string
    {
        return "CASE WHEN YEAR($column1) = YEAR($column2) THEN 1 ELSE 0 END";
    }
}