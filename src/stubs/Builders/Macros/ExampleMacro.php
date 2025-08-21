<?php

namespace App\Builders\Macros;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class ExampleMacro extends BaseMacro
{
    public static function name(): string
    {
        return 'selectExample';
    }

    public function defaultExpression($column): string
    {
        return "(" . $column . ")";
    }
} 