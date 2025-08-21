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
}