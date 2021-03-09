<?php
/*
 * GPLv3 https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Author: eidng8
 */

namespace eidng8\Laravel\Schema\Tests\Eloquent;

use eidng8\Laravel\Schema\Eloquent\UuidModel;
use Illuminate\Database\Connection;
use Illuminate\Support\Arr;
use Orchestra\Testbench\TestCase;
use Ramsey\Uuid\Uuid;

class UuidModelTest extends TestCase
{
  public function testKeyIsInitiallyNull()
  {
    $model = new UuidModel();
    self::assertNull($model->getKey());
  }

  public function testKeyIsFilledAutomatically()
  {
    $model = new UuidModel();
    self::assertSame(36, strlen($model->getKey(true)));
  }

  public function testInvalidKeyIsOverwritten()
  {
    $model = new UuidModel();
    $model->id = 'invalid';
    self::assertSame(36, strlen($model->getKey(true)));
  }

  public function testSetKeyAcceptsNull()
  {
    $model = new UuidModel();
    $model->setKey();
    self::assertEquals(36, strlen($model->getKey()));
  }

  public function testSetKeyAcceptsUuidInstance()
  {
    $uuid = Uuid::uuid1();
    $model = new UuidModel();
    $model->setKey($uuid);
    self::assertSame($uuid->toString(), $model->getKey());
  }

  public function testSetKeyAcceptsUuidString()
  {
    $uuid = Uuid::uuid1()->toString();
    $model = new UuidModel();
    $model->setKey($uuid);
    self::assertSame($uuid, $model->getKey());
  }

  public function testSetKeyAcceptsUuidBytes()
  {
    $uuid = Uuid::uuid1();
    $model = new UuidModel();
    $model->setKey($uuid->getBytes());
    self::assertSame($uuid->toString(), $model->getKey());
  }

  public function testSave()
  {
    $model = new UuidModel();
    $model->getConnection()->pretend(
      function (Connection $conn) use ($model) {
        $model->save();
        $queries = Arr::pluck(
          $conn->getQueryLog(),
          'bindings',
          'query'
        );
        self::assertSame(
          [
            'insert into `uuid_models` (`id`, `updated_at`, `created_at`) values (?, ?, ?)' => [
              $model->id,
              (string)$model->created_at,
              (string)$model->updated_at,
            ],
          ],
          $queries
        );
      }
    );
  }
}
