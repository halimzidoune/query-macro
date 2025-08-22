<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectReplace
 * Purpose: Replace all occurrences of a substring.
 * Example:
 *   - Given: title = "old news"
 *   - Usage: ->selectReplace('title as updated_title', 'old', 'new')
 *   - Result: updated_title = "new news"
 */
class Replace extends BaseMacro
{
    public static function name(): string
    {
        return 'selectReplace';
    }

    public function defaultExpression($column, $search, $replace): string
    {
        return "REPLACE($column, '$search', '$replace')";
    }

    public function mysql($column, $search, $replace): string
    {
        return $this->defaultExpression($column, $search, $replace);
    }

    public function pgsql($column, $search, $replace): string
    {
        return "REGEXP_REPLACE($column, '$search', '$replace', 'g')";
    }

    public function sqlsrv($column, $search, $replace): string
    {
        return $this->defaultExpression($column, $search, $replace);
    }

    public function oracle($column, $search, $replace): string
    {
        return "REGEXP_REPLACE($column, '$search', '$replace', 1, 0, 'i')";
    }
}