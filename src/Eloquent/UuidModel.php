<?php
/*
 * GPLv3 https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Author: eidng8
 */

namespace eidng8\Laravel\Schema\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Base class for all models with a UUID primary key. Don't use the primary key
 * columns directly. Use `getKey()` and `setKey()` instead. `getKey()` returns
 * a human friendly string of the UUID. `setKey()` makes sure the stored value
 * is in binary form. When `save()` is called, the instance will validate the
 * UUID, and fills in a valid one if necessary.
 */
class UuidModel extends Model
{
  public $incrementing = false;

  protected $keyType = 'uuid';

  /**
   * {@inheritDoc}
   *
   * @param boolean $autoGen Automatically generation a UUID if the instance
   *     doesn't have one.
   *
   * @return string A human friendly representation of the UUID.
   */
  public function getKey(bool $autoGen = false): ?string
  {
    $name = $this->getKeyName();
    $key = $this->attributes[$name] ?? null;
    if ($autoGen && strlen($key) != 16) {
      $this->setKey(Uuid::uuid1());
      $key = $this->attributes[$name];
    }
    if ($key) {
      return Uuid::fromBytes($key)->toString();
    }
    return null;
  }

  /**
   * Sets the primary key column's value to the given UUID.
   *
   * @param string|UuidInterface $uuid A UUID instance, human friendly
   *     representation of UUID, or binary UUID string. Generates a new
   *     version 1 UUID if `null`.
   *
   * @return void
   */
  public function setKey($uuid = null): void
  {
    if (null === $uuid) {
      $uuid = Uuid::uuid1();
    } elseif (is_string($uuid)) {
      $uuid = str_contains($uuid, '-')
        ? Uuid::fromString($uuid)
        : Uuid::fromBytes($uuid);
    }
    $this->attributes[$this->getKeyName()] = $uuid->getBytes();
  }

  /**
   * {@inheritDoc}
   */
  public function save(array $options = [])
  {
    $this->getKey(true);
    return parent::save($options);
  }
}
