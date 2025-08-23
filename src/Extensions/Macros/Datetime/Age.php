<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\Datetime;

use Hz\QueryMacroHelper\Extensions\BaseMacro;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class Age extends BaseMacro
{
    public static function name(): string
    {
        return 'selectAge';
    }

    public function defaultExpression($column, $reference = null): string
    {
        $ref = $this->normalizeReference($reference) ?: 'CURRENT_DATE';
        return "EXTRACT(YEAR FROM AGE($ref, $column))";
    }

    public function mysql($column, $reference = null): string
    {
        $ref = $this->normalizeReference($reference) ?: 'CURDATE()';
        return "TIMESTAMPDIFF(YEAR, $column, $ref)";
    }

    public function pgsql($column, $reference = null): string
    {
        $ref = $this->normalizeReference($reference) ?: 'CURRENT_DATE';
        return "FLOOR(EXTRACT(YEAR FROM AGE($ref, $column)))::integer";
    }

    public function sqlsrv($column, $reference = null): string
    {
        $ref = $this->normalizeReference($reference) ?: 'GETDATE()';
        return "DATEDIFF(YEAR, $column, $ref) - 
               CASE WHEN DATEADD(YEAR, DATEDIFF(YEAR, $column, $ref), $column) > $ref 
               THEN 1 ELSE 0 END";
    }

    public function oracle($column, $reference = null): string
    {
        $ref = $this->normalizeReference($reference) ?: 'SYSDATE';
        return "FLOOR(MONTHS_BETWEEN($ref, $column) / 12)";
    }

    public function sqlite($column, $reference = null): string
    {
        $ref = $this->normalizeReference($reference) ?: 'DATE()';
        return "CAST(strftime('%Y', $ref) AS INTEGER) - CAST(strftime('%Y', $column) AS INTEGER) - 
               (CASE WHEN strftime('%m-%d', $ref) < strftime('%m-%d', $column) THEN 1 ELSE 0 END)";
    }

    protected function normalizeReference($reference = null){
        if($reference){
            if($reference instanceof Stringable){
                $reference = "'".$reference->value()."'";
            }
        }
        return $reference;
    }
}