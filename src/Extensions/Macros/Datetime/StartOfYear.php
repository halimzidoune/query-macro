<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Trait\FormatDateExpressionTrait;

class StartOfYear extends BaseMacro
{
    use FormatDateExpressionTrait;

    public static function name(): string { return 'selectStartOfYear'; }

    public function defaultExpression($column, ?string $format = null): string
    {
        return $this->formatExpression("DATE_TRUNC('year', $column)", $format);
    }

    public function mysql($column, ?string $format = null): string
    {
        return $this->formatExpression("MAKEDATE(YEAR($column), 1)", $format);
    }

    public function pgsql($column, ?string $format = null): string
    {
        return $this->formatExpression("DATE_TRUNC('year', $column)", $format);
    }

    public function sqlsrv($column, ?string $format = null): string
    {
        return $this->formatExpression("DATEFROMPARTS(YEAR($column), 1, 1)", $format);
    }

    public function oracle($column, ?string $format = null): string
    {
        return $this->formatExpression("TRUNC($column, 'YEAR')", $format);
    }

    public function sqlite($column, ?string $format = null): string
    {
        return $this->formatExpression("DATE($column, 'start of year')", $format);
    }
}