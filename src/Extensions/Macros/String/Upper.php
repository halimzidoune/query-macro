<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Upper extends BaseMacro
{

    public static function name(): string
    {
        return 'selectUpper';
    }

    public function defaultExpression($column): string
    {
        return "Upper($column)";
    }

    public function mysql($column): string
    {
        return "Upper($column)";
    }
}