<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Lower extends BaseMacro
{

    public static function name(): string
    {
        return 'selectLower';
    }

    public function defaultExpression($column): string
    {
        return "LOWER($column)";
    }

    public function mysql($column): string
    {
        return "LOWER($column)";
    }
}