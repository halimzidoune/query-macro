<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class IsLeapYear extends BaseMacro
{
    public static function name(): string
    {
        return 'selectIsLeapYear';
    }

    public function defaultExpression($column): string
    {
        return "(EXTRACT(YEAR FROM $column) % 4 = 0 AND (EXTRACT(YEAR FROM $column) % 100 != 0 OR EXTRACT(YEAR FROM $column) % 400 = 0))";
    }

    public function mysql($column): string
    {
        return "(YEAR($column) % 4 = 0 AND (YEAR($column) % 100 != 0 OR YEAR($column) % 400 = 0))";
    }

    public function sqlsrv($column): string
    {
        return "CASE 
            WHEN (YEAR($column) % 4 = 0 
                  AND (YEAR($column) % 100 != 0 
                       OR YEAR($column) % 400 = 0)) 
            THEN 1 
            ELSE 0 
        END";
    }

    public function sqlite($column): string
    {
        return "(CAST(STRFTIME('%Y', $column) AS INTEGER) % 4 = 0 
            AND (CAST(STRFTIME('%Y', $column) AS INTEGER) % 100 != 0 
            OR CAST(STRFTIME('%Y', $column) AS INTEGER) % 400 = 0))";
    }

    public function oracle($column): string
    {
        return "(CASE 
                WHEN (MOD(EXTRACT(YEAR FROM $column), 4) = 0 
                      AND (MOD(EXTRACT(YEAR FROM $column), 100) != 0 
                           OR MOD(EXTRACT(YEAR FROM $column), 400) = 0)) 
                THEN 1 
                ELSE 0 
            END)";
    }

}
