<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Trait\FormatDateExpressionTrait;

class StartOfDay extends BaseMacro
{
    use FormatDateExpressionTrait;

    public static function name(): string
    {
        return 'selectStartOfDay';
    }

    public function defaultExpression($column, ?string $format = null): string
    {
        $result = "DATE_TRUNC('day', $column)";
        return $this->formatExpression($result, $format);
    }

    public function mysql($column, ?string $format = null): string
    {

        $result = "DATE($column)";
        return $this->formatExpression($result, $format);
    }

    public function pgsql($column, ?string $format = null): string
    {

        $result = "DATE_TRUNC('day', $column)";
        return $this->formatExpression($result, $format);
    }

    public function sqlsrv($column, ?string $format = null): string
    {

        $result = "CAST(FLOOR(CAST($column AS FLOAT)) AS DATETIME)";
        return $this->formatExpression($result, $format);
    }

    public function oracle($column, ?string $format = null): string
    {
        $result = "TRUNC($column)";
        return $this->formatExpression($result, $format);
    }

    public function sqlite($column, ?string $format = null): string
    {

        $result = "DATE($column, 'start of day')";
        return $this->formatExpression($result, $format);
    }
}