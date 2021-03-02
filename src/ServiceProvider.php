<?php
/*
 * GPLv3 https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Author: eidng8
 */

namespace eidng8\Laravel\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Support\Fluent;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
  public function register()
  {
  }

  public function boot()
  {
    $this->blueprintMacros();
    $this->grammarMacros();
  }

  private function blueprintMacros()
  {
    Blueprint::macro(
      'binary_',
      function ($column, $length = null) {
        $length = $length ?: Builder::$defaultStringLength;
        /* @var Blueprint $this */
        return $this->addColumn('binary_', $column, compact('length'));
      }
    );
    Blueprint::macro(
      'binary_v',
      function ($column, $length = null) {
        $length = $length ?: Builder::$defaultStringLength;
        /* @var Blueprint $this */
        return $this->addColumn('binary_v', $column, compact('length'));
      }
    );
    Blueprint::macro(
      'uuid_',
      function ($column) {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->binary_($column, 16);
      }
    );
  }

  private function grammarMacros()
  {
    Grammar::macro(
      'typeBinary_',
      function (Fluent $column) {
        switch (class_basename($this)) {
          case 'PostgresGrammar':
            return "bytea({$column->length})";

          case 'SQLiteGrammar':
            return "blob({$column->length})";

          default:
            return "binary({$column->length})";
        }
      }
    );

    Grammar::macro(
      'typeBinary_v',
      function (Fluent $column) {
        switch (class_basename($this)) {
          case 'PostgresGrammar':
            return "bytea({$column->length})";

          case 'SQLiteGrammar':
            return "blob({$column->length})";

          default:
            return "varbinary({$column->length})";
        }
      }
    );
  }
}
