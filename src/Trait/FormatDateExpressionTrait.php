<?php

namespace Hz\QueryMacroHelper\Trait;

trait FormatDateExpressionTrait
{
    /**
     * Apply formatting if requested
     */
    protected function formatExpression(string $expression, ?string $format): string
    {
        if ($format !== null) {
            return (new \Hz\QueryMacroHelper\Extensions\Macros\Datetime\FormatDate())->{$this->driver}($expression, $format);
        }

        return $expression;
    }
}