<?php

namespace PopHub\Model;


class Session {
  private $name;

  public function __construct($name, $value = null) {
    $this->name = $name;
    $this->setSession($value);
  }

  public static function set($key, $value) {
    $_SESSION[$key] = $value;
  }

  public static function get($name) {
    return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
  }

  public static function destroy($name) {
    unset($_SESSION[$name]);
  }
}