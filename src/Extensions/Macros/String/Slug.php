<?php

namespace Hz\QueryMacroHelper\Extensions\Macros\String;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

/**
 * Macro: selectSlug
 * Purpose: Create a URL-friendly slug (lowercase, spaces to hyphens, remove non-alphanumerics).
 * Example:
 *   - Given: title = "Head of Ops"
 *   - Usage: ->selectSlug('title as slug')
 *   - Result: slug = "head-of-ops"
 */
class Slug extends BaseMacro
{
    public static function name(): string
    {
        return 'selectSlug';
    }

    public function defaultExpression($column): string
    {
        return "LOWER(REPLACE(REPLACE(REPLACE(TRIM($column), ' ', '-'), '''', ''), '--', '-')";
    }
    
    public function mysql($column): string
    {
        return "LOWER(REPLACE(REGEXP_REPLACE(TRIM($column), '[^a-zA-Z0-9\\\\s]', ''), ' ', '-'))";
    }

    public function pgsql($column): string
    {
        return "LOWER(REGEXP_REPLACE(REGEXP_REPLACE(TRIM($column), '[^a-zA-Z0-9\\\\s]', '', 'g'), '\\\\s+', '-', 'g'))";
    }

    public function sqlsrv($column): string
    {
        return "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(LTRIM(RTRIM($column)), ' ', '-'), '''', ''), '--', '-'), '--', '-'))";
    }

    public function oracle($column): string
    {
        return "LOWER(REGEXP_REPLACE(REGEXP_REPLACE(TRIM($column), '[^a-zA-Z0-9\\\\s]', ''), '\\\\s+', '-'))";
    }

    public function sqlite($column): string
    {
        return "LOWER(REPLACE(REPLACE(TRIM($column), ' ', '-'), '''', ''))";
    }
}