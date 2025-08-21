<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Hz\QueryMacroHelper\Extensions\TimeUnit;

/**
 * Macro for adding time intervals to datetime columns
 *
 * Supports all major database systems with automatic interval conversion
 *
 */
class AddTime extends BaseMacro
{

    /**
     * Get the macro name for query builder registration
     *
     * @return string
     */
    public static function name(): string
    {
        return 'selectAddTime';
    }

    /**
     * Default SQL expression (PostgreSQL style)
     *
     * @param mixed $column Column name or expression
     * @param string $interval Interval unit (second, minute, hour, etc.)
     * @param int $value Number of intervals to add
     * @return string
     */
    public function defaultExpression($column, string $interval, int $value, ?string $format = null): string
    {
        $normalized = TimeUnit::normalizeInterval($interval);
        $result =  "$column + INTERVAL '$value $normalized'";
        if($format){
            return FormatDate::make()->getExpression($this->driver, $result, $format);
        }

        return $result;

    }


    /**
     * MySQL implementation using DATE_ADD
     */
    public function mysql($column, string $interval, int $value, ?string $format = null): string
    {
        $unit = TimeUnit::convertToMySQLUnit($interval);
        $result = "DATE_ADD($column, INTERVAL $value $unit)";

        if($format){
            return FormatDate::make()->getExpression($this->driver, $result, $format);
        }

        return $result;
    }



    /**
     * PostgreSQL implementation using INTERVAL
     */
    public function pgsql($column, string $interval, int $value, ?string $format = null): string
    {
        $normalized = TimeUnit::normalizeInterval($interval);
        $result = "$column + INTERVAL '$value $normalized'";
        if($format){
            return FormatDate::make()->getExpression($this->driver, $result, $format);
        }

        return $result;
    }

    /**
     * SQL Server implementation using DATEADD
     */
    public function sqlsrv($column, string $interval, int $value, ?string $format = null): string
    {
        $unit = TimeUnit::convertToSQLServerUnit($interval);
        $result = "DATEADD($unit, $value, $column)";
        if($format){
            return FormatDate::make()->getExpression($this->driver, $result, $format);
        }

        return $result;
    }


    /**
     * Oracle implementation using INTERVAL
     */
    public function oracle($column, string $interval, int $value, ?string $format = null): string
    {
        $unit = TimeUnit::convertToOracleUnit($interval);
        $result = "$column + INTERVAL '$value' $unit";
        if($format){
            return FormatDate::make()->getExpression($this->driver, $result, $format);
        }

        return $result;
    }


    /**
     * SQLite implementation using DATETIME modifier
     */
    public function sqlite($column, string $interval, int $value, ?string $format = null): string
    {
        $modifier = TimeUnit::convertToSQLiteModifier($interval);
        $sign = $value < 0 ? '-' : '+';
        $result = "DATETIME($column, '$sign" . abs($value) . " $modifier')";

        if($format){
            return FormatDate::make()->getExpression($this->driver, $result, $format);
        }

        return $result;
    }


}