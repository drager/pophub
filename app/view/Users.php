<?php

namespace PopHub\View;

class Users {

  /**
   * View for displaying ONE user.
   * @param Array $context
   */
  public function show(array $context) {
    print("<h1>" . $context["user"]->login . "</h1>");
    print("<p>Number of followers: " . $context["user"]->followers);
    print("<p>Number of public repos: " . $context["user"]->public_repos);
    $this->showUsersRepos($context["repos"]);
    $this->showUserFollowers($context["followers"]);
  }

  /**
   * View for displaying ALL the users depending on sorting
   * and paging.
   * @param Array $context 
   */
  public function showAllUsers(array $context) {
    // print $context["users"]->total_count;;

    // Add pagination at the top.
    $this->constructPagnation($context["pages"]);  

    if ($this->getSortBy() !== "repos") {
      print("<a href='?sort_by=repos'>Sortera på repos</a>");
    }
    else {
      print("<a href='/users/'>Sortera på följare (default)</a>");
    }

    print("
      <select name='select'>
        <option value='' selected>Sortera på språk</option> 
        <option value='php'>PHP</option>
        <option value='ruby'>Ruby</option>
        <option value='python'>Python</option>
      </select>
    ");

    foreach ($context["users"]->items as $count => $user) {
      print("<h2><a href='/users/$user->login'>" . $user->login . "</a></h2>");
      print("<img src='$user->avatar_url&s=96' height='96' />");
      // print("Nummer " . ($count+1) . " på listan");
      // print("<p>ID: " . $user->id . "</p>");
    }

    // Add pagination at the bottom at the page as well.
    $this->constructPagnation($context["pages"]);
  }

  /**
   * @return String
   */
  public function getSortBy() {
    if (isset($_GET["sort_by"])) {
      return $_GET["sort_by"];
    }
    else {
      return "";
    }
  }

  /**
   * @return String
   */
  public function getPage() {
    if (isset($_GET["page"])) {
      return $_GET["page"];
    }
    else {
      return "";
    }
  }

  /**
   * Sets up the pagination, if we sort by for instance "repos"
   * we want to have that sorting when we walking through pages.
   * @param Array $pages
   */
  private function constructPagnation(array $pages) {
    # TODO: Do better!
    $url = "<a href='/users/?";
    if ($this->getSortBy() !== "") {
      $url = "<a href='/users/?sort_by=" . $this->getSortBy() ."&";
    } else {
      $url = "<a href='/users/?";
    }

    if (isset($pages["first"])) {
      print($url . "'>Första sidan</a>");
    }

    if (isset($pages["next"])) {
      $nextPage = $pages["next"];
      $nextPage = preg_replace('/^.*page=\s*/', '', $nextPage);
      print($url . "page=" . $nextPage . "'>Nästa sida</a>");
    }

    if (isset($pages["last"])) {
      $lastPage = $pages["last"];
      $lastPage = preg_replace('/^.*page=\s*/', '', $lastPage);
      print($url . "page=" . $lastPage . "'>Sista sidan</a>");
    }
  }

  private function showUsersRepos(array $repos) {
    print("<h2>Repos on Github</h2>");
    print("<ul>");

    foreach ($repos as $repo) {
      print("<li>" . $repo->name . "</li>");
      // if ($repo->description) {
      //   print("<em>" . $repo->description . "</em>");
      // }
    }
    print("</ul>");
  }

  private function showUserFollowers(array $followers) {
    // var_dump($followers);

    print("<h2>Followers on Github</h2>");
    print("<ul>");

    foreach ($followers as $follower) {
      print("<li><a href='$follower->login'>" . $follower->login . "</a></li>");
    }

    print("</ul>");
  }
}