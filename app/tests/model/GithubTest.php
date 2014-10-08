<?php

require_once __DIR__.'/../../model/Github.php';


class GithubTest extends PHPUnit_Framework_TestCase {
  public function testCanGetAllUsersWithoutSorting() {
    $github = new Model\Github();

    // $github->getAllUsers();
  }
}