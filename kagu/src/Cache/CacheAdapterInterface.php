<?php

namespace Kagu\Cache;

interface CacheAdapterInterface {

  function connect();

  function get($key);

  function set($key, $value);

  function flush();
}