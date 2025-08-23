<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Casts;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * SelectCastToDecimal - Converts values to decimal with precision
 * Example: CAST('123.456' AS DECIMAL(10,2))
 */
class SelectFloat extends BaseMacro
{
    public const DEFAULT_PRECISION = 20;

    public static function name(): string
    {
        return 'selectFloat';
    }


    public function defaultExpression($column, $precision = self::DEFAULT_PRECISION, $scale = 2): string
    {
        return "CAST($column AS DECIMAL($precision,$scale))";
    }


    public function sqlsrv($column, $precision = self::DEFAULT_PRECISION, $scale = 2): string
    {
        return "CAST($column AS DECIMAL($precision,$scale))";
    }

    public function oracle($column, $precision = self::DEFAULT_PRECISION, $scale = 2): string
    {
        return "CAST($column AS NUMBER($precision,$scale))";
    }

    public function sqlite($column, $precision = self::DEFAULT_PRECISION, $scale = 2): string
    {
        return "CAST($column AS REAL)"; // SQLite doesn't support precision/scale in CAST
    }

    public function mysql($column, $precision = self::DEFAULT_PRECISION, $scale = 2): string
    {
        $decimal = "(CAST($column AS DECIMAL($precision, $scale)))";
        return "CAST($decimal AS DOUBLE)";
    }

    public function pgsql($column, $precision = self::DEFAULT_PRECISION, $scale = 2): string
    {
        // Utiliser une fonction PostgreSQL qui retourne toujours float
        return "TRUNC($column::numeric($precision,$scale) + 0.0, $scale)::double precision";
    }
}