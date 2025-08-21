<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Extensions\Macros\Datetime\FormatDate;

/**
 * Macro for getting the end of day (23:59:59)
 *
 * Supports:
 * - Optional time inclusion (23:59:59)
 * - Custom formatting
 * - All major database systems
 */
class EndOfDay extends BaseMacro
{
    /**
     * Get the macro name for query builder registration
     */
    public static function name(): string
    {
        return 'selectEndOfDay';
    }

    /**
     * Default implementation (PostgreSQL style)
     *
     * @param mixed $column Column name or expression
     * @param bool $endTime Include end-of-day time (23:59:59)
     * @param string|null $format Optional format string
     * @return string
     */
    public function defaultExpression($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "DATE_TRUNC('day', $column) + INTERVAL '1 day' - INTERVAL '1 second'"
            : "DATE_TRUNC('day', $column) + INTERVAL '1 day'";

        return $this->formatExpression($expr, $format);
    }

    /**
     * MySQL implementation
     */
    public function mysql($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "DATE_ADD(DATE_ADD(DATE($column), INTERVAL 1 DAY), INTERVAL -1 SECOND)"
            : "DATE_ADD(DATE($column), INTERVAL 1 DAY)";

        return $this->formatExpression($expr, $format);
    }

    /**
     * PostgreSQL implementation
     */
    public function pgsql($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "DATE_TRUNC('day', $column) + INTERVAL '1 day' - INTERVAL '1 second'"
            : "DATE_TRUNC('day', $column) + INTERVAL '1 day'";

        return $this->formatExpression($expr, $format);
    }

    /**
     * SQL Server implementation
     */
    public function sqlsrv($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "DATEADD(SECOND, -1, DATEADD(DAY, 1, CAST(CAST($column AS DATE) AS DATETIME))"
            : "DATEADD(DAY, 1, CAST(CAST($column AS DATE) AS DATETIME))";

        return $this->formatExpression($expr, $format);
    }

    /**
     * Oracle implementation
     */
    public function oracle($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "TRUNC($column) + 1 - INTERVAL '1' SECOND"
            : "TRUNC($column) + 1";

        return $this->formatExpression($expr, $format);
    }

    /**
     * SQLite implementation
     */
    public function sqlite($column, bool $endTime = true, ?string $format = null): string
    {
        $modifiers = $endTime
            ? ["'start of day'", "'+1 day'", "'-1 second'"]
            : ["'start of day'", "'+1 day'"];

        $expr = "DATETIME($column, " . implode(', ', $modifiers) . ")";

        return $this->formatExpression($expr, $format);
    }

    /**
     * Apply formatting if requested
     */
    protected function formatExpression(string $expression, ?string $format): string
    {
        if ($format !== null) {
            return FormatDate::make()->{$this->driver}($expression, $format);
        }

        return $expression;
    }
}