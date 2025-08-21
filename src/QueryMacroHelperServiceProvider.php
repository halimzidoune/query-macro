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
                    if ($instance->hasColumn()) {
                        $column = $args[0];
                        $parts = explode(' as ', $column);
                        $column = $parts[0];

                        if (count($parts) > 1) {
                            $alias = $parts[1];
                        } else {
                            $alias = $column . '_' . str(class_basename($instance))->snake('_');
                        }
                        $args[0] = $column;
                        $expression = $instance->getExpression($driver, ...$args);
                        return $this->selectRaw("{$expression} AS {$alias}");
                    } else {
                        $expression = $instance->getExpression($driver, ...$args);
                        return $this->selectRaw("{$expression}");
                    }

                });

                // Register for Eloquent\Builder
                \Illuminate\Database\Eloquent\Builder::macro($macroName, function (...$args) use ($macroName) {
                    return $this->toBase()->{$macroName}(...$args);
                });
            }
        }

    }

}
