<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Number;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectSubtract - Subtracts one number/column from another
 * Example: revenue - expenses
 */
class Subtract extends BaseMacro
{
    public static function name(): string { return 'selectSubtract'; }

    public function defaultExpression($column1, $column2): string {
        return "($column1 - $column2)";
    }
}