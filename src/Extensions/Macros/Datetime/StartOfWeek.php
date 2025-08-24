<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Trait\FormatDateExpressionTrait;

class StartOfWeek extends BaseMacro
{
    use FormatDateExpressionTrait;

    public static function name(): string
    {
        return 'selectStartOfWeek';
    }

    public function defaultExpression($column, ?string $format = null): string
    {
        $result = "DATE_TRUNC('week', $column)";
        return $this->formatExpression($result, $format);
    }

    public function mysql($column, ?string $format = null): string
{
    // Calculate days to subtract to get Monday
    // (DAYOFWEEK returns 1=Sunday, 2=Monday, ..., 7=Saturday)
    $result = "DATE_SUB($column, INTERVAL ((DAYOFWEEK($column) + 5) % 7) DAY)";
    return $this->formatExpression($result, $format);
}

    public function pgsql($column, ?string $format = null): string
    {
        $result = "DATE_TRUNC('week', $column)";
        return $this->formatExpression($result, $format);
    }

    public function sqlsrv($column, ?string $format = null): string
    {
        // SQL Server: DATEPART(WEEKDAY) returns 1=Sunday, 2=Monday, ..., 7=Saturday
        // To get Monday as start of week, subtract appropriate days
        // Formula: subtract (DATEPART(WEEKDAY) + 5) % 7 days to get to Monday
        $result = "DATEADD(DAY, -((DATEPART(WEEKDAY, $column) + 5) % 7), $column)";
        return $this->formatExpression($result, $format);
    }


    public function oracle($column, ?string $format = null): string
    {
        $result = "TRUNC($column, 'IW')";
        return $this->formatExpression($result, $format);
    }

    public function sqlite($column, ?string $format = null): string
    {
        $result = "DATE($column, 'weekday 0', '-6 days')";
        return $this->formatExpression($result, $format);
    }
}