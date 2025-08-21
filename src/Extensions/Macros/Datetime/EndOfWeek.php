<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Extensions\Macros\Datetime\FormatDate;
use Hz\QueryMacroHelper\Trait\FormatDateExpressionTrait;

/**
 * Macro for getting the end of week (Sunday)
 *
 * Supports:
 * - Optional time inclusion (23:59:59)
 * - Custom formatting
 * - All major database systems
 */
class EndOfWeek extends BaseMacro
{
    use FormatDateExpressionTrait;
    /**
     * Get the macro name for query builder registration
     */
    public static function name(): string
    {
        return 'selectEndOfWeek';
    }

    /**
     * Default implementation (PostgreSQL style)
     *
     * @param mixed $column Column name or expression
     * @param bool $endDay Include end-of-day time (23:59:59)
     * @param string|null $format Optional format string
     * @return string
     */
    public function defaultExpression($column, bool $endDay = true, ?string $format = null): string
    {
        $time = $endDay ? "23:59:59'" : "'";
        $expr = "(DATE_TRUNC('week', $column) + INTERVAL '6 days $time)";

        return $this->formatExpression($expr, $format);
    }

    /**
     * MySQL implementation
     */
    public function mysql($column, bool $endDay = true, ?string $format = null): string
    {
        $time = $endDay ? "23:59:59" : "00:00:00";
        $expr = "DATE_ADD(DATE_ADD(DATE_SUB($column, INTERVAL (DAYOFWEEK($column) - 1 DAY), INTERVAL 6 DAY $time)";

        return $this->formatExpression($expr, $format);
    }

    /**
     * PostgreSQL implementation
     */
    public function pgsql($column, bool $endDay = true, ?string $format = null): string
    {
        $time = $endDay ? "23:59:59'" : "'";
        $expr = "(DATE_TRUNC('week', $column) + INTERVAL '6 days $time)";

        return $this->formatExpression($expr, $format);
    }

    /**
     * SQL Server implementation
     */
    public function sqlsrv($column, bool $endDay = true, ?string $format = null): string
    {
        $expr = $endDay
            ? "DATEADD(SECOND, -1, DATEADD(DAY, 7 - DATEPART(WEEKDAY, $column), DATEADD(DAY, DATEDIFF(DAY, 0, $column), 0)))"
            : "DATEADD(DAY, 6 - DATEPART(WEEKDAY, $column), DATEADD(DAY, DATEDIFF(DAY, 0, $column), 0))";

        return $this->formatExpression($expr, $format);
    }

    /**
     * Oracle implementation
     */
    public function oracle($column, bool $endDay = true, ?string $format = null): string
    {
        $expr = $endDay
            ? "TRUNC($column, 'IW') + 6 + INTERVAL '23:59:59' HOUR TO SECOND"
            : "TRUNC($column, 'IW') + 6";

        return $this->formatExpression($expr, $format);
    }

    /**
     * SQLite implementation
     */
    public function sqlite($column, bool $endDay = true, ?string $format = null): string
    {
        $modifiers = $endDay
            ? ["'weekday 0'", "'23 hours'", "'59 minutes'", "'59 seconds'"]
            : ["'weekday 0'"];

        $expr = "DATETIME($column, " . implode(', ', $modifiers) . ")";

        return $this->formatExpression($expr, $format);
    }

}