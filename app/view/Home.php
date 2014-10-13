<?php

namespace PopHub\View;

class Home {
  public function showHome() {
    echo "<h1>PopHub</h1>";
    echo "<p><a href='/users/'>Go to all the users</a></p>";
    if (isset($_SESSION["access_token"]) === false) {
      echo "<p><a href='/login/'>Sign in through Github</a></p>";
    }
    else {
      echo "<p><a href='/user/'>Min profil</a></p>";
      echo "<p><a href='/logout/'>Logga ut</a></p>";
    }
  }
}