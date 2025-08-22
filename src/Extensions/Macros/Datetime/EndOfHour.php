<?php
namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Trait\FormatDateExpressionTrait;
class EndOfHour extends BaseMacro
{
    use FormatDateExpressionTrait;

    public static function name(): string { return 'selectEndOfHour'; }

    public function defaultExpression($column, ?string $format = null): string
    {
        return $this->formatExpression("DATE_TRUNC('hour', $column) + INTERVAL '1 hour' - INTERVAL '1 second'", $format);
    }

    public function oracle($column, ?string $format = null): string
    {
        return $this->formatExpression("TRUNC($column, 'HH24') + INTERVAL '59:59' MINUTE TO SECOND", $format);
    }

    public function mysql($column, ?string $format = null): string
    {
        $expr = "DATE_ADD(
            DATE_FORMAT($column, '%Y-%m-%d %H:00:00'),
            INTERVAL 3599 SECOND
        )";
        
        return $this->formatExpression($expr, $format);
    }

    public function sqlite($column, ?string $format = null): string
    {
        $expr = "DATETIME($column, 'start of hour', '+59 minutes', '+59 seconds')";
        return $this->formatExpression($expr, $format);
    }
}