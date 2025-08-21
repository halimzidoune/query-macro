# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-12-19

### Added
- Initial release of Laravel Query Macro Helper
- Database-agnostic select macros for String operations (14 macros)
- Database-agnostic select macros for Number operations (16 macros)  
- Database-agnostic select macros for DateTime operations (25 macros)
- Database-agnostic select macros for Type Casting (6 macros)
- Support for MySQL, PostgreSQL, SQLite, SQL Server, and Oracle
- Artisan command `make:macro` for creating custom macros
- Laravel auto-discovery support
- Comprehensive documentation with examples

### Supported Database Drivers
- MySQL
- PostgreSQL  
- SQLite
- SQL Server
- Oracle

### Macro Categories
- **String**: concat, upper, lower, length, substring, replace, trim, pad, startsWith, endsWith, contains, regexp, slug, case
- **Number**: add, subtract, multiply, abs, round, floor, ceil, power, sqrt, modulo, percent, truncate, random, randomBetween, safeDivision
- **DateTime**: formatDate, startOfDay, endOfDay, startOfWeek, endOfWeek, startOfYear, endOfYear, startOfHour, endOfHour, dayOfWeek, weekOfYear, daysInMonth, age, diffInDays, diffInMinutes, diffInSeconds, addTime, isSameDay, isSameYear, isSameHour, isSameMinute, isLeapYear, endOfMonth
- **Casts**: selectString, selectInteger, selectFloat, selectBoolean, selectDate, selectDateTime 