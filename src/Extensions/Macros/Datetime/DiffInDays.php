<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class DiffInDays extends BaseMacro
{
    public static function name(): string
    {
        return 'selectDiffInDays';
    }

    public function defaultExpression($column1, $column2): string
    {
        return "EXTRACT(DAY FROM ($column2 - $column1))";
    }

    public function mysql($column1, $column2): string
    {
        return "DATEDIFF($column2, $column1)";
    }

    public function pgsql($column1, $column2): string
    {
        return "EXTRACT(DAY FROM ($column2 - $column1))";
    }

    public function sqlsrv($column1, $column2): string
    {
        return "DATEDIFF(DAY, $column1, $column2)";
    }

    public function oracle($column1, $column2): string
    {
        return "$column2 - $column1";
    }

    public function sqlite($column1, $column2): string
    {
        return "JULIANDAY($column2) - JULIANDAY($column1)";
    }
}