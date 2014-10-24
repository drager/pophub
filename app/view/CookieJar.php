<?php

namespace PopHub\View;

class CookieJar {

  /**
   * Sets a cookie
   * @param String $string
   */
  public function set($name, $value, $time = 0, $path = "/") {
    setcookie($name, $value, $time, $path);
  }


  /**
   * Gets a cookie
   * @param String $name
   * @return String
   */
  public function get($name) {
    $ret = isset($_COOKIE[$name]) ? $_COOKIE[$name] : "";

    setcookie($name, null, -1, "/");

    return $ret;
  }

}
