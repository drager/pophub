<?php

namespace PopHub\Model;


class Session {
  // private $name;
  // private $value;

  // public function __construct($name, $value = null) {
  //   $this->name = $name;
  //   $this->value = $value;
  // }

  public function set($key, $value) {
    $_SESSION[$key] = $value;
  }

  public function get($name) {
    return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
  }

  public function destroy($name) {
    unset($_SESSION[$name]);
  }
}