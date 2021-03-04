<?php
/*
 * GPLv3 https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Author: eidng8
 */

namespace eidng8\Laravel\Schema\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;

class SQLiteTest extends TestCase
{
  public function test_binary_column()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $table->binary_('test_column');
      }
    );
    $this->assertSql(
      ['alter table "test_table" add column "test_column" blob(255) not null'],
      $blueprint
    );
  }

  public function test_binary_column_with_length()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $table->binary_('test_column', 16);
      }
    );
    $this->assertSql(
      ['alter table "test_table" add column "test_column" blob(16) not null'],
      $blueprint
    );
  }

  public function test_varbinary_column()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $table->binary_v('test_column');
      }
    );
    $this->assertSql(
      ['alter table "test_table" add column "test_column" blob(255) not null'],
      $blueprint
    );
  }

  public function test_varbinary_column_with_length()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $table->binary_v('test_column', 16);
      }
    );
    $this->assertSql(
      ['alter table "test_table" add column "test_column" blob(16) not null'],
      $blueprint
    );
  }

  public function test_uuid_column()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $table->uuid_('test_column');
      }
    );
    $this->assertSql(
      ['alter table "test_table" add column "test_column" blob(16) not null'],
      $blueprint
    );
  }

  public function test_uuid_foreign_for_model()
  {
    $model = new TestModel();
    $model->setKeyType('uuid');
    $blueprint = new Blueprint(
      'test_table',
      function ($table) use ($model) {
        $table->foreignIdFor_($model, 'test_id')->constrained();
      }
    );
    $this->assertSql(
      [
        'alter table "test_table" add column "test_id" blob(16) not null',
        // SQLite doesn't generate foreign key constraint
      ],
      $blueprint
    );
  }

  public function test_id_foreign_for_model()
  {
    $model = new TestModel();
    $model->setKeyType('int');
    $blueprint = new Blueprint(
      'test_table',
      function ($table) use ($model) {
        $table->foreignIdFor_($model, 'test_id')->constrained();
      }
    );
    $this->assertSql(
      [
        'alter table "test_table" add column "test_id" integer not null',
        // SQLite doesn't generate foreign key constraint
      ],
      $blueprint
    );
  }

  protected function grammar(): Grammar
  {
    config(['database.connections.testing.foreign_key_constraints' => true]);
    return new SQLiteGrammar();
  }
}
