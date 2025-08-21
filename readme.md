# Laravel Query Macro Helper

An extension layer for Laravel's Query Builder that registers portable, database-aware select macros. Write expressive `select...` helpers once and they will render the correct SQL for MySQL, PostgreSQL, SQLite, SQL Server, and Oracle—without `DB::raw()` or driver conditionals.

## 🚀 Features

- **Database Agnostic**: Automatically adapts SQL syntax for MySQL, PostgreSQL, SQLite, SQL Server, and Oracle
- **Clean API**: Simple, intuitive method names that work like native Laravel methods
- **No Raw SQL**: Eliminate the need for `DB::raw()` statements and driver-specific code
- **Easy Extension**: Create custom macros with the built-in artisan command
- **Laravel 10 & 11 Support**: Compatible with the latest Laravel versions

## ✨ Benefits

- **Single code path**: One query works across all supported drivers
- **Readable queries**: Intent-revealing `selectX()` methods instead of raw SQL
- **Safer expressions**: Centralize SQL generation and avoid copy/paste errors
- **Easy to extend**: Add your own macros with a tiny class; auto-registered
- **Composable**: Chain multiple macros with standard Query/Eloquent builders
- **Production-ready**: Covers common String, Number, Datetime, and Cast use cases

## 📦 Installation

### Via Composer

```bash
composer require halimzidoune/query-macro-helper
```

### Service Provider Registration

The package will auto-register, but if you need manual control, add this to your `config/app.php`:

```php
'providers' => [
    // ...
    Hz\QueryMacroHelper\QueryMacroHelperServiceProvider::class,
],
```

### Publishing Extensions (Optional)

```bash
php artisan vendor:publish --tag=query-extensions
```

## ✍️ Writing a custom macro

Use the generator, then implement driver-aware SQL as needed.

```bash
php artisan make:macro Lower
```

This creates `app/Builders/Macros/Lower.php`. Example implementation:

```php
<?php

namespace App\Builders\Macros;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class Lower extends BaseMacro
{
    public static function name(): string
    {
        return 'selectLower';
    }

    // First argument is the column with optional alias; additional args are your parameters
    public function defaultExpression($column): string
    {
        // LOWER() function exists on all major databases
        return "LOWER($column)";
    }

    // Optionally, override per driver if needed:
    // public function mysql($column) { return "LOWER($column)"; }
    // public function pgsql($column) { return "LOWER($column)"; }
}
```

Usage:

```php
DB::table('users')
    ->selectLower('email as email_lower')
    ->get();
```

**Example 2: Driver-specific implementation**

Here's a more complex example that shows how to handle different database drivers:

```php
<?php

namespace App\Builders\Macros;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class ExtractYear extends BaseMacro
{
    public static function name(): string
    {
        return 'selectExtractYear';
    }

    // Default implementation (PostgreSQL style)
    public function defaultExpression($column): string
    {
        return "EXTRACT(YEAR FROM $column)";
    }

    // MySQL implementation
    public function mysql($column): string
    {
        return "YEAR($column)";
    }

    // SQL Server implementation
    public function sqlsrv($column): string
    {
        return "YEAR($column)";
    }

    // Oracle implementation
    public function oracle($column): string
    {
        return "EXTRACT(YEAR FROM $column)";
    }

    // SQLite implementation
    public function sqlite($column): string
    {
        return "strftime('%Y', $column)";
    }
}
```

Usage:

```php
DB::table('users')
    ->selectExtractYear('birth_date as birth_year')
    ->get();

// Automatically generates the correct SQL for each database:
// MySQL: YEAR(birth_date) AS birth_year
// PostgreSQL: EXTRACT(YEAR FROM birth_date) AS birth_year
// SQL Server: YEAR(birth_date) AS birth_year
// Oracle: EXTRACT(YEAR FROM birth_date) AS birth_year
// SQLite: strftime('%Y', birth_date) AS birth_year
```

**Note**: When creating custom macros that accept multiple arguments, consider using `str()` helper for literal strings to distinguish them from column names, similar to how `selectConcat` works.

More ideas you could implement:
- `selectNullIfEmpty(column)`: Treat empty string as NULL across drivers
- `selectQuarter(dateColumn)`: Extract quarter number Q1–Q4
- `selectJsonValue(column, path)`: JSON path extraction with cross-driver support
- `selectNormalizePhone(column)`: Keep digits only for phone normalization

## 📚 Macro reference (quick table)

Below are the macros shipped with the package, grouped by category. Call these as chained methods on `Query\Builder` or `Eloquent\Builder`. Aliases can be provided using `"column as alias"`.

### String

| Macro | Purpose |
| --- | --- |
| `selectConcat` | Concatenate columns/values |
| `selectUpper` | Uppercase text |
| `selectLower` | Lowercase text |
| `selectLength` | String length |
| `selectSubstring` | Substring by start/length |
| `selectReplace` | Replace substring |
| `selectTrim` | Trim whitespace |
| `selectPad` | Pad string to length |
| `selectStartsWith` | Starts-with check |
| `selectEndsWith` | Ends-with check |
| `selectContains` | Contains check |
| `selectRegexp` | Regex match flag/value |
| `selectSlug` | URL-friendly slug |
| `selectCase` | SQL CASE mapping |

### Number

| Macro | Purpose |
| --- | --- |
| `selectAdd` | Addition |
| `selectSubtract` | Subtraction |
| `selectMultiply` | Multiplication |
| `selectAbs` | Absolute value |
| `selectRound` | Round to decimals |
| `selectFloor` | Floor |
| `selectCeil` | Ceil |
| `selectPower` | Power/exponent |
| `selectSqrt` | Square root |
| `selectModulo` | Modulo/remainder |
| `selectPercent` | Percentage of total |
| `selectTruncate` | Truncate decimals |
| `selectRandom` | Random number |
| `selectRandomBetween` | Random in range |
| `selectSafeDivision` | Divide with zero-safe fallback |

### Datetime

| Macro | Purpose |
| --- | --- |
| `selectDateFormat` | Format date/time with Carbon-like tokens |
| `selectStartOfDay` | Start of day |
| `selectEndOfDay` | End of day |
| `selectStartOfWeek` | Start of ISO week |
| `selectEndOfWeek` | End of ISO week |
| `selectStartOfYear` | Start of year |
| `selectEndOfYear` | End of year |
| `selectStartOfHour` | Start of hour |
| `selectEndOfHour` | End of hour |
| `selectDayOfWeek` | Day of week number |
| `selectWeekOfYear` | ISO week number |
| `selectDaysInMonth` | Days count in month |
| `selectAge` | Age from date |
| `selectDiffInDays` | Difference in days |
| `selectDiffInMinutes` | Difference in minutes |
| `selectDiffInSeconds` | Difference in seconds |
| `selectAddTime` | Add interval to datetime |
| `selectIsSameDay` | Same calendar day? |
| `selectIsSameYear` | Same calendar year? |
| `selectIsSameHour` | Same hour? |
| `selectIsSameMinute` | Same minute? |
| `selectEndOfMonth` | End of month |

### Casts

| Macro | Purpose |
| --- | --- |
| `selectString` | Cast to string (varchar/text) |
| `selectInteger` | Cast to integer |
| `selectFloat` | Cast to float/decimal |
| `selectBoolean` | Cast to boolean |
| `selectDate` | Cast to date |
| `selectDateTime` | Cast to datetime |

### 🔧 Available Macros

#### `selectConcat(result_alias, column1, ...)`
Concatenates multiple columns or values with database-specific syntax.

**Important**: Use `str()` helper for literal strings to distinguish them from column names.

```php
// Works on all databases automatically
DB::table('users')
    ->selectConcat('full_name', 'first_name', str(' '), 'last_name')
    ->get();

// Column names: 'first_name', 'last_name'
// Literal string: str(' ') for the space separator
// Result: first_name + ' ' + last_name AS full_name

// MySQL: CONCAT(first_name, ' ', last_name) AS full_name
// PostgreSQL/SQLite: first_name || ' ' || last_name AS full_name
// SQL Server: ISNULL(first_name, '') + ' ' + ISNULL(last_name, '') AS full_name
```

**More examples:**

```php
// Concatenate with custom separator
DB::table('products')
    ->selectConcat('full_name', 'brand', str(' - '), 'model')
    ->get();

// Concatenate with multiple literals
DB::table('addresses')
    ->selectConcat('full_address', 'street', str(', '), 'city', str(', '), 'state')
    ->get();

// Mix columns and literals
DB::table('users')
    ->selectConcat('display_name', str('@'), 'username')
    ->get();
```

#### `selectUpper(column)`
Converts text to uppercase.

```php
DB::table('users')
    ->selectUpper('name as name_upper')
    ->get();
```

#### `selectLower(column)`
Converts text to lowercase.

```php
DB::table('users')
    ->selectLower('email as email_lower')
    ->get();
```

#### `selectLength(column)`
Gets the length of a string.

```php
DB::table('posts')
    ->selectLength('content as content_length')
    ->get();
```

#### `selectSubstring(column, start, length)`
Extracts a substring from a string.

```php
DB::table('users')
    ->selectSubstring('email as email_prefix', 1, 10)
    ->get();
```

#### `selectReplace(column, search, replace)`
Replaces occurrences of a substring.

```php
DB::table('posts')
    ->selectReplace('title as updated_title', 'old', 'new')
    ->get();
```

#### `selectTrim(column)`
Removes leading and trailing whitespace.

```php
DB::table('users')
    ->selectTrim('username as clean_username')
    ->get();
```

#### `selectPad(column, length, pad_string, pad_type)`
Pads a string to a specified length.

```php
DB::table('products')
    ->selectPad('sku as padded_sku', 8, '0', 'left')
    ->get();
```

#### `selectStartsWith(column, prefix)`
Checks if a string starts with a specific prefix.

```php
DB::table('files')
    ->selectStartsWith('filename as is_image', 'IMG_')
    ->get();
```

#### `selectEndsWith(column, suffix)`
Checks if a string ends with a specific suffix.

```php
DB::table('files')
    ->selectEndsWith('filename as is_jpg', '.jpg')
    ->get();
```

#### `selectContains(column, substring)`
Checks if a string contains a substring.

```php
DB::table('posts')
    ->selectContains('content as has_important', 'important')
    ->get();
```

#### `selectRegexp(column, pattern)`
Performs regex pattern matching.

```php
DB::table('users')
    ->selectRegexp('email as valid_email', '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$')
    ->get();
```

#### `selectSlug(column)`
Converts text to URL-friendly slug format.

```php
DB::table('posts')
    ->selectSlug('title as url_slug')
    ->get();
```

#### `selectCase(column, cases, default)`
Performs CASE statement operations.

```php
DB::table('users')
    ->selectCase('status as status_label', [
        'active' => 'Active User',
        'inactive' => 'Inactive User'
    ], 'Unknown')
    ->get();
```

### Number Operations

#### `selectAdd(column1, column2)`
Adds two numeric values.

```php
DB::table('orders')
    ->selectAdd('subtotal as total', 'tax')
    ->get();
```

#### `selectSubtract(column1, column2)`
Subtracts one numeric value from another.

```php
DB::table('inventory')
    ->selectSubtract('stock as available', 'reserved')
    ->get();
```

#### `selectMultiply(column1, column2)`
Multiplies two numeric values.

```php
DB::table('order_items')
    ->selectMultiply('quantity as line_total', 'unit_price')
    ->get();
```

#### `selectRandom()`
Generates a random number (database-specific implementation).

```php
DB::table('users')
    ->selectRandom('random_value')
    ->get();
```

#### `selectRandomBetween(min, max)`
Generates a random number within a range.

```php
DB::table('products')
    ->selectRandomBetween('random_priority', 1, 100)
    ->get();
```

#### `selectAbs(column)`
Returns the absolute value of a number.

```php
DB::table('transactions')
    ->selectAbs('amount as absolute_amount')
    ->get();
```

#### `selectRound(column, decimals)`
Rounds a number to a specified number of decimal places.

```php
DB::table('products')
    ->selectRound('price as rounded_price', 2)
    ->get();
```

#### `selectFloor(column)`
Rounds a number down to the nearest integer.

```php
DB::table('measurements')
    ->selectFloor('length as floor_length')
    ->get();
```

#### `selectCeil(column)`
Rounds a number up to the nearest integer.

```php
DB::table('measurements')
    ->selectCeil('length as ceil_length')
    ->get();
```

#### `selectPower(column, exponent)`
Raises a number to a specified power.

```php
DB::table('squares')
    ->selectPower('number as squared', 2)
    ->get();
```

#### `selectSqrt(column)`
Calculates the square root of a number.

```php
DB::table('geometry')
    ->selectSqrt('area as side_length')
    ->get();
```

#### `selectModulo(column, divisor)`
Returns the remainder of division.

```php
DB::table('numbers')
    ->selectModulo('value as is_even', 2)
    ->get();
```

#### `selectPercent(column, total)`
Calculates percentage.

```php
DB::table('sales')
    ->selectPercent('amount as percentage', 'total_sales')
    ->get();
```

#### `selectTruncate(column, decimals)`
Truncates a number to a specified number of decimal places.

```php
DB::table('prices')
    ->selectTruncate('cost as truncated_cost', 2)
    ->get();
```

#### `selectSafeDivision(column1, column2, default)`
Performs safe division with fallback for division by zero.

```php
DB::table('calculations')
    ->selectSafeDivision('numerator as result', 'denominator', 0)
    ->get();
```

### DateTime Operations

#### `selectDateFormat(column, format)`
Formats a date using Carbon-style format strings.

```php
DB::table('users')
    ->selectDateFormat('created_at as formatted_date', 'Y-m-d H:i:s')
    ->get();

// Automatically converts to database-specific format:
// MySQL: DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s')
// PostgreSQL: TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI:SS')
// SQL Server: FORMAT(created_at, 'yyyy-MM-dd HH:mm:ss')
```

#### `selectStartOfDay(column)`
Gets the start of day for a date.

```php
DB::table('events')
    ->selectStartOfDay('event_date as day_start')
    ->get();
```

#### `selectEndOfDay(column)`
Gets the end of day for a date.

```php
DB::table('events')
    ->selectEndOfDay('event_date as day_end')
    ->get();
```

#### `selectStartOfWeek(column)`
Gets the start of week for a date.

```php
DB::table('schedules')
    ->selectStartOfWeek('week_date as week_start')
    ->get();
```

#### `selectEndOfWeek(column)`
Gets the end of week for a date.

```php
DB::table('schedules')
    ->selectEndOfWeek('week_date as week_end')
    ->get();
```

#### `selectEndOfMonth(column)`
Gets the end of month for a date.

```php
DB::table('reports')
    ->selectEndOfMonth('report_date as month_end')
    ->get();
```

#### `selectStartOfYear(column)`
Gets the start of year for a date.

```php
DB::table('fiscal_years')
    ->selectStartOfYear('fiscal_date as year_start')
    ->get();
```

#### `selectEndOfYear(column)`
Gets the end of year for a date.

```php
DB::table('fiscal_years')
    ->selectEndOfYear('fiscal_date as year_end')
    ->get();
```

#### `selectStartOfHour(column)`
Gets the start of hour for a datetime.

```php
DB::table('logs')
    ->selectStartOfHour('timestamp as hour_start')
    ->get();
```

#### `selectEndOfHour(column)`
Gets the end of hour for a datetime.

```php
DB::table('logs')
    ->selectEndOfHour('timestamp as hour_end')
    ->get();
```

#### `selectDayOfWeek(column)`
Gets the day of week (1-7).

```php
DB::table('events')
    ->selectDayOfWeek('event_date as day_number')
    ->get();
```

#### `selectWeekOfYear(column)`
Gets the week number of the year.

```php
DB::table('schedules')
    ->selectWeekOfYear('date as week_number')
    ->get();
```

#### `selectDaysInMonth(column)`
Gets the number of days in a month.

```php
DB::table('calendar')
    ->selectDaysInMonth('month_date as days_count')
    ->get();
```

#### `selectAge(column)`
Calculates age from a birth date.

```php
DB::table('users')
    ->selectAge('birth_date as age')
    ->get();
```

#### `selectDiffInDays(column1, column2)`
Calculates the difference in days between two dates.

```php
DB::table('bookings')
    ->selectDiffInDays('check_in as stay_duration', 'check_out')
    ->get();
```

#### `selectDiffInMinutes(column1, column2)`
Calculates the difference in minutes between two datetimes.

```php
DB::table('calls')
    ->selectDiffInMinutes('start_time as call_duration', 'end_time')
    ->get();
```

#### `selectDiffInSeconds(column1, column2)`
Calculates the difference in seconds between two datetimes.

```php
DB::table('races')
    ->selectDiffInSeconds('start_time as race_time', 'finish_time')
    ->get();
```

#### `selectAddTime(column, amount, unit)`
Adds time to a date/datetime.

```php
DB::table('appointments')
    ->selectAddTime('scheduled_time as next_hour', 1, 'hour')
    ->get();
```

#### `selectIsSameDay(column1, column2)`
Checks if two dates are the same day.

```php
DB::table('events')
    ->selectIsSameDay('event_date as is_today', 'today')
    ->get();
```

#### `selectIsSameYear(column1, column2)`
Checks if two dates are in the same year.

```php
DB::table('fiscal_records')
    ->selectIsSameYear('fiscal_date as is_current_fiscal', 'current_year')
    ->get();
```

#### `selectIsSameHour(column1, column2)`
Checks if two datetimes are in the same hour.

```php
DB::table('logs')
    ->selectIsSameHour('timestamp as is_current_hour', 'now')
    ->get();
```

#### `selectIsSameMinute(column1, column2)`
Checks if two datetimes are in the same minute.

```php
DB::table('high_frequency_data')
    ->selectIsSameMinute('timestamp as is_current_minute', 'now')
    ->get();
```

#### `selectIsLeapYear(column)`
Checks if a year is a leap year.

```php
DB::table('years')
    ->selectIsLeapYear('year_date as is_leap')
    ->get();
```

### Type Casting Operations

#### `selectString(column, length)`
Casts a value to string type.

```php
DB::table('products')
    ->selectString('sku as sku_string', 50)
    ->get();
```

#### `selectInteger(column)`
Casts a value to integer type.

```php
DB::table('prices')
    ->selectInteger('amount as amount_int')
    ->get();
```

#### `selectFloat(column, precision, scale)`
Casts a value to float type.

```php
DB::table('measurements')
    ->selectFloat('value as value_float', 10, 2)
    ->get();
```

#### `selectBoolean(column)`
Casts a value to boolean type.

```php
DB::table('settings')
    ->selectBoolean('is_active as active_bool')
    ->get();
```

#### `selectDate(column)`
Casts a value to date type.

```php
DB::table('events')
    ->selectDate('datetime as date_only')
    ->get();
```

#### `selectDateTime(column)`
Casts a value to datetime type.

```php
DB::table('logs')
    ->selectDateTime('timestamp as formatted_datetime')
    ->get();
```

## 🛠️ Creating Custom Macros

### Using Artisan Command

```bash
php artisan make:macro MyCustomMacro
```

This will create a new macro class in `app/Builders/Macros/` directory.

### Manual Creation

Create a class that extends `BaseMacro` and implements the required methods:

```php
<?php

namespace App\Builders\Macros;

use Hz\QueryMacroHelper\Extensions\BaseMacro;

class CustomMacro extends BaseMacro
{
    public static function name(): string
    {
        return 'selectCustom';
    }

    public function defaultExpression($column, $param): string
    {
        return "CUSTOM_FUNCTION($column, $param)";
    }

    public function mysql($column, $param): string
    {
        return "MYSQL_CUSTOM_FUNCTION($column, $param)";
    }

    public function pgsql($column, $param): string
    {
        return "POSTGRES_CUSTOM_FUNCTION($column, $param)";
    }

    // Add other database-specific methods as needed
}
```

## 🔍 Advanced Usage Examples

### Complex Queries with Multiple Macros

```php
$users = DB::table('users')
    ->selectConcat('full_name', 'first_name', str(' '), 'last_name')
    ->selectDateFormat('created_at as join_date', 'Y-m-d')
    ->selectAge('birth_date as age')
    ->selectCase('status as status_label', [
        'active' => 'Active',
        'inactive' => 'Inactive'
    ], 'Unknown')
    ->where('age', '>', 18)
    ->get();
```

### Using with Eloquent Models

```php
$posts = Post::query()
    ->selectSlug('title as url_slug')
    ->selectLength('content as content_length')
    ->selectDateFormat('published_at as formatted_date', 'F j, Y')
    ->where('content_length', '>', 1000)
    ->get();
```

## 🌟 Supported Database Drivers

- **MySQL** - Full support for all macros
- **PostgreSQL** - Full support for all macros
- **SQLite** - Full support for all macros
- **SQL Server** - Full support for all macros
- **Oracle** - Full support for all macros

## 📚 Best Practices

1. **Use Descriptive Aliases**: Always provide meaningful aliases for your macro results
2. **Combine with Existing Methods**: Mix macros with standard Laravel query methods
3. **Performance Considerations**: Some macros may be more efficient than others on specific databases
4. **Testing**: Test your queries across different database drivers when possible

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## 🆘 Support

If you encounter any issues or have questions, please open an issue on GitHub or contact the maintainer.

---

**Made with ❤️ for the Laravel community**
