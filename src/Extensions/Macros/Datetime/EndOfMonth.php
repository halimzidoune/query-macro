<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Extensions\Macros\Datetime\FormatDate;
use Hz\QueryMacroHelper\Trait\FormatDateExpressionTrait;

/**
 * Macro for getting the end of month
 *
 * Supports:
 * - Optional time inclusion (23:59:59)
 * - Custom formatting
 * - All major database systems
 */
class EndOfMonth extends BaseMacro
{
    use FormatDateExpressionTrait;
    /**
     * Get the macro name for query builder registration
     */
    public static function name(): string
    {
        return 'selectEndOfMonth';
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
            ? "(DATE_TRUNC('month', $column) + INTERVAL '1 month' - INTERVAL '1 second')"
            : "DATE_TRUNC('month', $column) + INTERVAL '1 month'";

        return $this->formatExpression($expr, $format);
    }

    /**
     * MySQL implementation
     */
    public function mysql($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "LAST_DAY($column) + INTERVAL '23:59:59'"
            : "LAST_DAY($column)";

        return $this->formatExpression($expr, $format);
    }

    /**
     * PostgreSQL implementation
     */
    public function pgsql($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "(DATE_TRUNC('month', $column) + INTERVAL '1 month' - INTERVAL '1 second')"
            : "DATE_TRUNC('month', $column) + INTERVAL '1 month'";

        return $this->formatExpression($expr, $format);
    }

    /**
     * SQL Server implementation
     */
    public function sqlsrv($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "DATEADD(SECOND, -1, DATEADD(MONTH, 1, DATEFROMPARTS(YEAR($column), MONTH($column), 1)))"
            : "EOMONTH($column)";

        return $this->formatExpression($expr, $format);
    }

    /**
     * Oracle implementation
     */
    public function oracle($column, bool $endTime = true, ?string $format = null): string
    {
        $expr = $endTime
            ? "LAST_DAY($column) + INTERVAL '23:59:59' HOUR TO SECOND"
            : "LAST_DAY($column)";

        return $this->formatExpression($expr, $format);
    }

    /**
     * SQLite implementation
     */
    public function sqlite($column, bool $endTime = true, ?string $format = null): string
    {
        $modifiers = $endTime
            ? ["'start of month'", "'+1 month'", "'-1 second'"]
            : ["'start of month'", "'+1 month'"];

        $expr = "DATETIME($column, " . implode(', ', $modifiers) . ")";

        return $this->formatExpression($expr, $format);
    }



}