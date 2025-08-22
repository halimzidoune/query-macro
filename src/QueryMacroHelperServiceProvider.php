<?php

namespace Hz\QueryMacroHelper;

use Closure;
use Hz\QueryMacroHelper\Command\MakeMacro;
use Hz\QueryMacroHelper\Extensions\Contracts\QueryExtensionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class QueryMacroHelperServiceProvider extends ServiceProvider
{
    public const DRIVER_KEYWORDS = [
        'mysql' => [
            'ACCESSIBLE', 'ADD', 'ALL', 'ALTER', 'ANALYZE', 'AND', 'AS', 'ASC', 'ASENSITIVE', 'BEFORE', 'BETWEEN', 'BIGINT',
            'BINARY', 'BLOB', 'BOTH', 'BY', 'CALL', 'CASCADE', 'CASE', 'CHANGE', 'CHAR', 'CHARACTER', 'CHECK', 'COLLATE',
            'COLUMN', 'CONDITION', 'CONSTRAINT', 'CONTINUE', 'CONVERT', 'CREATE', 'CROSS', 'CURRENT_DATE', 'CURRENT_TIME',
            'CURRENT_TIMESTAMP', 'CURRENT_USER', 'CURSOR', 'DATABASE', 'DATABASES', 'DAY_HOUR', 'DAY_MICROSECOND',
            'DAY_MINUTE', 'DAY_SECOND', 'DEC', 'DECIMAL', 'DECLARE', 'DEFAULT', 'DELAYED', 'DELETE', 'DESC', 'DESCRIBE',
            'DETERMINISTIC', 'DISTINCT', 'DISTINCTROW', 'DIV', 'DOUBLE', 'DROP', 'DUAL', 'EACH', 'ELSE', 'ELSEIF', 'ENCLOSED',
            'ESCAPED', 'EXISTS', 'EXIT', 'EXPLAIN', 'FALSE', 'FETCH', 'FLOAT', 'FOR', 'FORCE', 'FOREIGN', 'FROM', 'FULLTEXT',
            'GENERATED', 'GET', 'GRANT', 'GROUP', 'HAVING', 'HIGH_PRIORITY', 'HOUR_MICROSECOND', 'HOUR_MINUTE', 'HOUR_SECOND',
            'IF', 'IGNORE', 'IN', 'INDEX', 'INFILE', 'INNER', 'INOUT', 'INSENSITIVE', 'INSERT', 'INT', 'INTEGER', 'INTERVAL',
            'INTO', 'IO_AFTER_GTIDS', 'IO_BEFORE_GTIDS', 'IS', 'ITERATE', 'JOIN', 'KEY', 'KEYS', 'KILL', 'LEADING', 'LEAVE',
            'LEFT', 'LIKE', 'LIMIT', 'LINEAR', 'LINES', 'LOAD', 'LOCALTIME', 'LOCALTIMESTAMP', 'LOCK', 'LONG', 'LONGBLOB',
            'LONGTEXT', 'LOOP', 'LOW_PRIORITY', 'MASTER_BIND', 'MASTER_SSL_VERIFY_SERVER_CERT', 'MATCH', 'MAXVALUE',
            'MEDIUMBLOB', 'MEDIUMINT', 'MEDIUMTEXT', 'MIDDLEINT', 'MINUTE_MICROSECOND', 'MINUTE_SECOND', 'MOD', 'MODIFIES',
            'NATURAL', 'NOT', 'NO_WRITE_TO_BINLOG', 'NULL', 'NUMERIC', 'ON', 'OPTIMIZE', 'OPTIMIZER_COSTS', 'OPTION',
            'OPTIONALLY', 'OR', 'ORDER', 'OUT', 'OUTER', 'OUTFILE', 'PARTITION', 'PRECISION', 'PRIMARY', 'PROCEDURE', 'PURGE',
            'RANGE', 'READ', 'READS', 'REAL', 'REFERENCES', 'REGEXP', 'RELEASE', 'RENAME', 'REPEAT', 'REPLACE', 'REQUIRE',
            'RESIGNAL', 'RESTRICT', 'RETURN', 'REVOKE', 'RIGHT', 'RLIKE', 'SCHEMA', 'SCHEMAS', 'SECOND_MICROSECOND', 'SELECT',
            'SENSITIVE', 'SEPARATE', 'SET', 'SHOW', 'SIGNAL', 'SMALLINT', 'SPATIAL', 'SPECIFIC', 'SQL', 'SQL_BIG_RESULT',
            'SQL_CALC_FOUND_ROWS', 'SQL_SMALL_RESULT', 'SQLEXCEPTION', 'SQLSTATE', 'SQLWARNING', 'SSL', 'STARTING', 'STORED',
            'STRAIGHT_JOIN', 'TABLE', 'TABLES', 'TABLESPACE', 'TERMINATED', 'THEN', 'TO', 'TRAILING', 'TRIGGER', 'TRUE',
            'UNDO', 'UNION', 'UNIQUE', 'UNLOCK', 'UNSIGNED', 'UPDATE', 'USAGE', 'USE', 'USING', 'UTC_DATE', 'UTC_TIME',
            'UTC_TIMESTAMP', 'VALUES', 'VARBINARY', 'VARCHAR', 'VARCHARACTER', 'VARYING', 'WHEN', 'WHERE', 'WHILE', 'WITH',
            'WRITE', 'XOR', 'YEAR_MONTH', 'ZEROFILL'
        ],
        'pgsql' => [
            'ALL', 'ANALYSE', 'ANALYZE', 'AND', 'ANY', 'ARRAY', 'AS', 'ASC', 'ASYMMETRIC', 'AUTHORIZATION', 'BINARY', 'BOTH',
            'CASE', 'CAST', 'CHECK', 'COLLATE', 'COLUMN', 'CONSTRAINT', 'CREATE', 'CROSS', 'CURRENT_CATALOG', 'CURRENT_DATE',
            'CURRENT_ROLE', 'CURRENT_SCHEMA', 'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'CURRENT_USER', 'DEFAULT', 'DEFERRABLE',
            'DESC', 'DISTINCT', 'DO', 'ELSE', 'END', 'EXCEPT', 'FALSE', 'FOR', 'FOREIGN', 'FROM', 'FULL', 'GRANT', 'GROUP',
            'HAVING', 'ILIKE', 'IN', 'INITIALLY', 'INNER', 'INTERSECT', 'INTO', 'IS', 'ISNULL', 'JOIN', 'LEADING', 'LEFT',
            'LIKE', 'LIMIT', 'LOCALTIME', 'LOCALTIMESTAMP', 'NATURAL', 'NOT', 'NOTNULL', 'NULL', 'OFF', 'OFFSET', 'ON', 'ONLY',
            'OR', 'ORDER', 'OUTER', 'OVER', 'OVERLAPS', 'PLACING', 'PRIMARY', 'REFERENCES', 'RETURNING', 'RIGHT', 'SELECT',
            'SESSION_USER', 'SIMILAR', 'SOME', 'SYMMETRIC', 'TABLE', 'THEN', 'TO', 'TRAILING', 'TRUE', 'UNION', 'UNIQUE',
            'USER', 'USING', 'VARIADIC', 'VERBOSE', 'WHEN', 'WHERE', 'WINDOW', 'WITH'
        ],
        'sqlite' => [
            'ABORT', 'ACTION', 'ADD', 'AFTER', 'ALL', 'ALTER', 'ANALYZE', 'AND', 'AS', 'ASC', 'ATTACH', 'AUTOINCREMENT',
            'BEFORE', 'BEGIN', 'BETWEEN', 'BY', 'CASCADE', 'CASE', 'CAST', 'CHECK', 'COLLATE', 'COLUMN', 'COMMIT', 'CONFLICT',
            'CONSTRAINT', 'CREATE', 'CROSS', 'CURRENT_DATE', 'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'DATABASE', 'DEFAULT',
            'DEFERRABLE', 'DEFERRED', 'DELETE', 'DESC', 'DETACH', 'DISTINCT', 'DROP', 'EACH', 'ELSE', 'END', 'ESCAPE',
            'EXCEPT', 'EXCLUSIVE', 'EXISTS', 'EXPLAIN', 'FAIL', 'FOR', 'FOREIGN', 'FROM', 'FULL', 'GLOB', 'GROUP', 'HAVING',
            'IF', 'IGNORE', 'IMMEDIATE', 'IN', 'INDEX', 'INDEXED', 'INITIALLY', 'INNER', 'INSERT', 'INSTEAD', 'INTERSECT',
            'INTO', 'IS', 'ISNULL', 'JOIN', 'KEY', 'LEFT', 'LIKE', 'LIMIT', 'MATCH', 'NATURAL', 'NO', 'NOT', 'NOTNULL',
            'NULL', 'OF', 'OFFSET', 'ON', 'OR', 'ORDER', 'OUTER', 'PLAN', 'PRAGMA', 'PRIMARY', 'QUERY', 'RAISE', 'RECURSIVE',
            'REFERENCES', 'REGEXP', 'REINDEX', 'RELEASE', 'RENAME', 'REPLACE', 'RESTRICT', 'RIGHT', 'ROLLBACK', 'ROW',
            'SAVEPOINT', 'SELECT', 'SET', 'TABLE', 'TEMP', 'TEMPORARY', 'THEN', 'TO', 'TRANSACTIONS', 'TRIGGER', 'UNION',
            'UNIQUE', 'UPDATE', 'USING', 'VACUUM', 'VALUES', 'VIEW', 'VIRTUAL', 'WHEN', 'WHERE', 'WITH', 'WITHOUT'
        ],
        'sqlsrv' => [
            'ADD', 'ALL', 'ALTER', 'AND', 'ANY', 'AS', 'ASC', 'AUTHORIZATION', 'BACKUP', 'BEGIN', 'BETWEEN', 'BREAK', 'BROWSE',
            'BULK', 'BY', 'CASCADE', 'CASE', 'CHECK', 'CHECKPOINT', 'CLOSE', 'CLUSTERED', 'COALESCE', 'COLLATE', 'COLUMN',
            'COMMIT', 'COMPUTE', 'CONSTRAINT', 'CONTAINS', 'CONTAINSTABLE', 'CONTINUE', 'CONVERT', 'CREATE', 'CROSS', 'CURRENT',
            'CURRENT_DATE', 'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'CURRENT_USER', 'CURSOR', 'DATABASE', 'DBCC', 'DEALLOCATE',
            'DECLARE', 'DEFAULT', 'DELETE', 'DENY', 'DESC', 'DISK', 'DISTINCT', 'DISTRIBUTED', 'DOUBLE', 'DROP', 'DUMP', 'ELSE',
            'END', 'ERRLVL', 'ESCAPE', 'EXCEPT', 'EXEC', 'EXECUTE', 'EXISTS', 'EXIT', 'EXTERNAL', 'FETCH', 'FILE', 'FILLFACTOR',
            'FOR', 'FOREIGN', 'FREETEXT', 'FREETEXTTABLE', 'FROM', 'FULL', 'FUNCTION', 'GOTO', 'GRANT', 'GROUP', 'HAVING',
            'HOLDLOCK', 'IDENTITY', 'IDENTITY_INSERT', 'IDENTITYCOL', 'IF', 'IN', 'INDEX', 'INNER', 'INSERT', 'INTERSECT', 'INTO',
            'IS', 'JOIN', 'KEY', 'KILL', 'LEFT', 'LIKE', 'LINENO', 'LOAD', 'MERGE', 'NATIONAL', 'NOCHECK', 'NONCLUSTERED', 'NOT',
            'NULL', 'NULLIF', 'OF', 'OFF', 'OFFSETS', 'ON', 'OPEN', 'OPENDATASOURCE', 'OPENQUERY', 'OPENROWSET', 'OPENXML', 'OPTION',
            'OR', 'ORDER', 'OUTER', 'OVER', 'PERCENT', 'PIVOT', 'PLAN', 'PRECISION', 'PRIMARY', 'PRINT', 'PROC', 'PROCEDURE',
            'PUBLIC', 'RAISERROR', 'READ', 'READTEXT', 'RECONFIGURE', 'REFERENCES', 'REPLICATION', 'RESTORE', 'RESTRICT', 'RETURN',
            'REVERT', 'REVOKE', 'RIGHT', 'ROLLBACK', 'ROWCOUNT', 'ROWGUIDCOL', 'RULE', 'SAVE', 'SCHEMA', 'SECURITYAUDIT', 'SELECT',
            'SESSION_USER', 'SET', 'SETUSER', 'SHUTDOWN', 'SOME', 'STATISTICS', 'SYSTEM_USER', 'TABLE', 'TABLESAMPLE', 'TEXTSIZE',
            'THEN', 'TO', 'TOP', 'TRAN', 'TRANSACTION', 'TRIGGER', 'TRUNCATE', 'TSEQUAL', 'UNION', 'UNIQUE', 'UNPIVOT', 'UPDATE',
            'UPDATETEXT', 'USE', 'USER', 'VALUES', 'VARYING', 'VIEW', 'WAITFOR', 'WHEN', 'WHERE', 'WHILE', 'WITH', 'WITHIN GROUP',
            'WRITETEXT'
        ],
        'oracle' => [
            'DATE', 'date'
        ]
    ];

    public static function validateAlias(string $alias, string $driver): void
    {

        $driver = strtolower($driver);

        if (isset(QueryMacroHelperServiceProvider::DRIVER_KEYWORDS[$driver])) {
            $reservedKeywords = array_map('strtolower', self::DRIVER_KEYWORDS[$driver]);

            if (in_array(strtolower($alias), $reservedKeywords)) {

                throw new \Exception(
                    "The alias '{$alias}' is a reserved keyword in {$driver}. " .
                    "Please use a different alias name or escape it with backticks manually."
                );
            }
        }
    }
    public function register(): void
    {
    }

    public function boot(): void
    {
        $this->commands(MakeMacro::class);
        $this->publishes([
            __DIR__ . '/stubs/Builders/Macros' => app_path('Builders/Macros'),
        ], 'query-extensions');
        $this->registerExtensions();
    }

    protected function registerExtensions(): void
    {
        $driver = DB::getDriverName();

        $extensionNamespaces = [
            'Hz\\QueryMacroHelper\\Extensions\\Macros\\String' => __DIR__ . '/Extensions/Macros/String',
            'Hz\\QueryMacroHelper\\Extensions\\Macros\\Datetime' => __DIR__ . '/Extensions/Macros/Datetime',
            'Hz\\QueryMacroHelper\\Extensions\\Macros\\Casts' => __DIR__ . '/Extensions/Macros/Casts',
            'Hz\\QueryMacroHelper\\Extensions\\Macros\\Number' => __DIR__ . '/Extensions/Macros/Number',
            'App\\Builders\\Macros' => app_path('Builders/Macros'),
        ];

        foreach ($extensionNamespaces as $namespace => $directory) {
            if (!is_dir($directory)) {
                continue;
            }

            foreach (glob($directory . '/*.php') as $file) {
                require_once $file;

                $class = $namespace . '\\' . basename($file, '.php');

                if (!class_exists($class) || !is_subclass_of($class, QueryExtensionInterface::class)) {
                    continue;
                }

                $instance = app($class);
                $macroName = $class::name();

                // Register for Query\Builder
                \Illuminate\Database\Query\Builder::macro($macroName, function (...$args) use ($instance, $driver) {
                    // Ensure base columns are selected if none are set yet
                    if (empty($this->columns)) {
                        $this->select($this->from . '.*');
                    }

                    if ($instance->hasColumn()) {
                        $column = $args[0];
                        $parts = explode(' as ', $column);
                        $column = $parts[0];

                        if (count($parts) > 1) {
                            $alias = $parts[1];

                            QueryMacroHelperServiceProvider::validateAlias($alias, $driver);
                        } else {
                            $alias = $column . '_' . str(class_basename($instance))->snake('_');
                        }
                        $args[0] = $column;
                        $expression = $instance->getExpression($driver, ...$args);
                        return $this->addSelect(DB::raw("{$expression} AS {$alias}"));
                    } else {
                        $expression = $instance->getExpression($driver, ...$args);
                        return $this->addSelect(DB::raw("{$expression}"));
                    }

                });

                // Register for Eloquent\Builder
                \Illuminate\Database\Eloquent\Builder::macro($macroName, function (...$args) use ($instance, $driver) {
                    $baseQuery = $this->getQuery();

                    // Ensure base columns are selected if none are set yet
                    if (empty($baseQuery->columns)) {
                        $baseQuery->select($baseQuery->from . '.*');
                    }

                    if ($instance->hasColumn()) {
                        $column = $args[0];
                        $parts = explode(' as ', $column);
                        $column = $parts[0];

                        if (count($parts) > 1) {
                            $alias = $parts[1];

                            QueryMacroHelperServiceProvider::validateAlias($alias,$driver);
                        } else {
                            $alias = $column . '_' . str(class_basename($instance))->snake('_');
                        }
                        $args[0] = $column;
                        $expression = $instance->getExpression($driver, ...$args);
                        $this->getQuery()->addSelect(DB::raw("{$expression} AS {$alias}"));
                    } else {
                        $expression = $instance->getExpression($driver, ...$args);
                        $this->getQuery()->addSelect(DB::raw("{$expression}"));
                    }

                    return $this;
                });
            }
        }

    }

}
