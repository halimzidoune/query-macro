<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Trait\FormatDateExpressionTrait;

class EndOfYear extends BaseMacro
{
    use FormatDateExpressionTrait;

    public static function name(): string { return 'selectEndOfYear'; }

    public function defaultExpression($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = "DATE_TRUNC('year', $column) + INTERVAL '1 year'";
        $expr = $endTime ? "$expr - INTERVAL '1 second'" : $expr;
        return $this->formatExpression($expr, $format);
    }

    public function mysql($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = "MAKEDATE(YEAR($column), 1) + INTERVAL 1 YEAR";
        $expr = $endTime ? "$expr - INTERVAL 1 SECOND" : $expr;
        return $this->formatExpression($expr, $format);
    }

    public function sqlsrv($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = "DATEFROMPARTS(YEAR($column), 12, 31)";
        $expr = $endTime ? "DATEADD(SECOND, -1, DATEADD(DAY, 1, $expr))" : $expr;
        return $this->formatExpression($expr, $format);
    }

    public function sqlite($column, bool $endTime = true, ?string $format = null): string
    {
        $modifiers = ["'start of year'", "'+1 year'"];
        $expr = "DATETIME($column, " . implode(', ', $modifiers) . ")";
        $expr = $endTime ? "DATETIME($expr, '-1 second')" : $expr;
        return $this->formatExpression($expr, $format);
    }

    public function oracle($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = "ADD_MONTHS(TRUNC($column, 'YEAR'), 12) - INTERVAL '1' DAY";
        
        if ($endTime) {
            $expr = "$expr + INTERVAL '23:59:59' HOUR TO SECOND";
        }
        
        return $this->formatExpression($expr, $format);
    }
}