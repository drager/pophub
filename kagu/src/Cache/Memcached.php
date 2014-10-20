<?php

namespace Kagu\Cache;

use Kagu\Config\Config;

class Memcached {

  private $config;

  private $memcached;

  public function __construct(Config $config) {
    $this->config = $config;
    $this->memcached = new \Memcached();
    if ($this->connect() === false) {
      throw new \Exception("Trouble connecting to memcached!");
    }
  }

  public function connect() {
    $memcached = $this->memcached->addServer($this->config->get("MEMCACHED_HOST"), $this->config->get("MEMCACHED_PORT"));

    return $memcached;
  }

  public function get($key) {
    $value = $this->memcached->get($key);

    return $value;
  }

  public function set($key, $value, $time = 0) {
    $this->memcached->set($key, $value, $time);
  }

  public function flush() {
    $this->memcached->flush();
  }

  public function getResultCode() {
    return $this->memcached->getResultCode();
  }
}