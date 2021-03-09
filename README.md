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

### `foreignUuid_($column)`

Creates a UUID column and its corresponding foreign key.

### `foreignIdFor_($model, $column = null)`

Adds UUID support to eloquent's `foreignIdFor()` method.

## `eidng8\Laravel\Schema\Eloquent\UuidModel`

This model handles common processes of UUID, such as generating UUID
automatically.

Models inheriting this should *not* use the primary key columns directly.
Use `getKey()` and `setKey()` instead.

### `getKey(bool $autoGen = false): ?string`

Returns a human friendly string of the UUID. If `$autoGen` is `true`,
automatically generation a UUID if the instance doesn't have one.

### `setKey($uuid = null): void`

Sets the primary key column's value to the given UUID. Makes sure the stored
value is in binary form. The `$uuid` can be a UUID instance, human friendly
representation of UUID, binary UUID string, or `null`. A version 1 UUID will be
generated if `null`.
