<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class IsSameMinute extends BaseMacro
{
    public static function name(): string { return 'selectIsSameMinute'; }

    public function defaultExpression($column1, $column2): string
    {
        return "DATE_TRUNC('minute', $column1) = DATE_TRUNC('minute', $column2)";
    }


    public function sqlite($column1, $column2): string
    {
        return "STRFTIME('%Y-%m-%d %H:%M', $column1) = STRFTIME('%Y-%m-%d %H:%M', $column2)";
    }

    public function mysql($column1, $column2): string
    {
        return "MINUTE($column1) = MINUTE($column2)";
    }

    public function oracle($column1, $column2): string
    {
        return "CASE WHEN TRUNC($column1, 'MI') = TRUNC($column2, 'MI') THEN 1 ELSE 0 END";
    }

    public function sqlsrv($column1, $column2): string
    {
        return "CASE WHEN FORMAT($column1, 'yyyy-MM-dd HH:mm') = FORMAT($column2, 'yyyy-MM-dd HH:mm') THEN 1 ELSE 0 END";
    }


}