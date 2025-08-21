<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Dev;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectAverage - Calculates arithmetic mean
 * Example: AVG(salary)
 */
class Average extends BaseMacro
{
    public static function name(): string
    {
        return 'selectAverage';
    }

    public function defaultExpression($column): string
    {
        return "AVG($column)";
    }
}