<?php

namespace PopHub\View;

class Error {
  public function showServerError() {
    echo "<h1>500</h1>";

    echo "<p>Något gick fel!</p>";
  }

  public function showPageNotFound($page) {
    echo "Oops, de ser ut som om $page inte riktigt finns...\n";
  }
}