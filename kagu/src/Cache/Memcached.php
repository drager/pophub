<?php

namespace Kagu\Cache;

use Kagu\Config\Config;

class Memcached {

  private $config;

  private $memcached;


  /**
   * @param Config $config
   * @return void
   */
  public function __construct(Config $config) {
    $this->config = $config;
    $this->memcached = new \Memcached();

    if ($this->connect() === false) {
      throw new \Exception("Trouble connecting to memcached!");
    }
  }

  /**
   * Connect to memcached
   * @return Memcached object
   */
  public function connect() {
    $memcached = $this->memcached->addServer($this->config->get("MEMCACHED_HOST"), $this->config->get("MEMCACHED_PORT"));

    return $memcached;
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
  public function set($key, $value, $time = 0) {
    $this->memcached->set($key, $value, $time);
  }

  /**
   * Flush (empty the cache)
   */
  public function flush() {
    $this->memcached->flush();
  }

  public function getResultCode() {
    return $this->memcached->getResultCode();
  }
}