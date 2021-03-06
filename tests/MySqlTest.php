<?php
/*
 * GPLv3 https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Author: eidng8
 */

namespace eidng8\Laravel\Schema\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Database\Schema\Grammars\MySqlGrammar;

class TestModel extends Model
{
}

class MySqlTest extends TestCase
{
  public function test_binary_column()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $table->binary_('test_column');
      }
    );
    // $blueprint->foreignUuid_('wechat_user_id')->constrained();
    $this->assertSql(
      ['alter table `test_table` add `test_column` binary(255) not null'],
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
      ['alter table `test_table` add `test_column` binary(16) not null'],
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
      ['alter table `test_table` add `test_column` varbinary(255) not null'],
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
      ['alter table `test_table` add `test_column` varbinary(16) not null'],
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
      ['alter table `test_table` add `test_column` binary(16) not null'],
      $blueprint
    );
  }

  public function test_uuid_foreign_key()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $table->foreignUuid_('test_id')->constrained();
      }
    );
    $this->assertSql(
      [
        'alter table `test_table` add `test_id` binary(16) not null',
        'alter table `test_table` add constraint `test_table_test_id_foreign` foreign key (`test_id`) references `tests` (`id`)',
      ],
      $blueprint
    );
  }

  public function test_uuid_foreign_for_model()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $model = new TestModel();
        $model->setKeyType('uuid');
        $table->foreignIdFor_($model, 'test_id')->constrained();
      }
    );
    $this->assertSql(
      [
        'alter table `test_table` add `test_id` binary(16) not null',
        'alter table `test_table` add constraint `test_table_test_id_foreign` foreign key (`test_id`) references `tests` (`id`)',
      ],
      $blueprint
    );
  }

  public function test_id_foreign_for_model()
  {
    $blueprint = new Blueprint(
      'test_table',
      function ($table) {
        $table->foreignIdFor_(TestModel::class, 'test_id')->constrained();
      }
    );
    $this->assertSql(
      [
        'alter table `test_table` add `test_id` bigint unsigned not null',
        'alter table `test_table` add constraint `test_table_test_id_foreign` foreign key (`test_id`) references `tests` (`id`)',
      ],
      $blueprint
    );
  }

  protected function grammar(): Grammar
  {
    return new MySqlGrammar();
  }
}
