<?php

namespace Kagu\Cache;

use Kagu\Config\Config;

class MemcachedAdapter implements CacheAdapterInterface {

  private $memcached;

  /**
   * @param Memcached $memcached
   * @return void
   */
  public function __construct(Memcached $memcached) {
    $this->memcached = $memcached;
  }

  /**
   * Connect to memcached
   * @return Memcached object
   */
  public function connect() {
    return $this->memcached->connect();
  }

  /**
   * Get the given value from the cache
   * @param String $key
   * @return String
   */
  public function get($key) {
    $value = $this->memcached->get($key);

    return $value;
  }

  /**
   * Set the given value at key place
   * @param String $key
   * @param String $value
   * @return void
   */
  public function set($key, $value) {
    $this->memcached->set($key, $value);
  }

  public function flush() {
    return $this->memcached->flush();
  }

  public function getResultCode() {
    return $this->memcached->getResultCode();
  }
}