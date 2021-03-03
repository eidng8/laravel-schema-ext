# laravel-schema-ext

There package is intend to supplement specific aspects of Laravel's migration (schema). All methods added by this package have underscore suffix, or a few characters following the underscore.

## Methods

Currently there are only 3 methods in this package.

### `binary_($column, $length = null)`

This method adds a `binary` column to the table. Besides the capability of setting the column length, other differences with Laravel's `binary` method are:

|  Database  | ext             | Laravel   |
|------------|-----------------|-----------|
| MySQL      | binary($length) | blob      |
| PostgreSQL | bytea($length)  | bytea     |
| SQLite     | blob($length)   | blob      |
| SQL Server | binary($length) | varbinary |

### `binary_v($column, $length = null)`

This method adds a `varbinary` column to the table. Besides the capability of setting the column length, other differences with Laravel's `binary` method are:

|  Database  | ext                | Laravel   |
|------------|--------------------|-----------|
| MySQL      | varbinary($length) | blob      |
| PostgreSQL | bytea($length)     | bytea     |
| SQLite     | blob($length)      | blob      |
| SQL Server | varbinary($length) | varbinary |

### `uuid_($column)`

This is a shorthand of `binary_($column, 16)`.
