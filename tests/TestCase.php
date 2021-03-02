<?php
/*
 * GPLv3 https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Author: eidng8
 */

namespace eidng8\Laravel\Schema\Tests;

use eidng8\Laravel\Schema\ServiceProvider;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  protected function getPackageProviders($app)
  {
    return [ServiceProvider::class];
  }

  protected function connection(): Connection
  {
    /** @noinspection PhpUndefinedClassInspection PhpFullyQualifiedNameUsageInspection */
    return \DB::connection('testing');
  }

  protected function assertSql(string $expected, Blueprint $blueprint): void
  {
    static::assertEquals(
      $expected,
      $blueprint->toSql($this->connection(), $this->grammar())[0]
    );
  }

  abstract protected function grammar(): Grammar;
}
