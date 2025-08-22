<?php
namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Trait\FormatDateExpressionTrait;

class StartOfHour extends BaseMacro
{
    use FormatDateExpressionTrait;

    public static function name(): string { return 'selectStartOfHour'; }

    public function defaultExpression($column, ?string $format = null): string
    {
        return $this->formatExpression("DATE_TRUNC('hour', $column)", $format);
    }

    public function mysql($column, ?string $format = null): string
    {
        return $this->formatExpression("DATE_FORMAT($column, '%Y-%m-%d %H:00:00')", $format);
    }

    public function sqlsrv($column, ?string $format = null): string
    {
        return $this->formatExpression("DATETIMEFROMPARTS(YEAR($column), MONTH($column), DAY($column), DATEPART(HOUR, $column), 0, 0, 0)", $format);
    }

    public function sqlite($column, ?string $format = null): string
    {
        $expr = "STRFTIME('%Y-%m-%d %H:00:00', $column)";
        return $this->formatExpression($expr, $format);
    }

    public function oracle($column, ?string $format = null): string
    {
        return $this->formatExpression("TRUNC($column, 'HH24')", $format);
    }
}