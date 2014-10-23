<?php

namespace PopHub\Model;

use Kagu\Cache;
use Kagu\Config\Config;

class GithubAdapter implements ServiceInterface {

  private $github;

  /**
   * @param Github $github
   * @return void
   */
  public function __construct(Github $github) {
    $config = new Config(__DIR__."/../config/app.php");
    $this->github = $github;
    $this->memcached = new Cache\MemcachedAdapter(new Cache\Memcached($config));
  }

  /**
   * Authorize the user using GitHub Oauth.
   */
  public function authorize() {
    return $this->github->authorize();
  }

  /**
   * Post the code so we can get an token back.
   * @param String $code
   * @return String
   */
  public function postAccessToken($code) {
    return $this->github->postAccessToken($code);
  }

  /**
   * Get the currently logged in user using an accessToken
   * @param String $accessToken
   * @return User object
   */
  public function getLoggedInUser($accessToken) {
    try {
      $data = $this->github->getLoggedInUser($accessToken);
    } catch (\Exception $e) {
      return null;
    }

    $login = $data->login;
    $avatar = $data->avatar_url;
    $name = isset($data->name) ? $data->name : null;
    $email = isset($data->email) ? $data->email : null;
    $location = isset($data->location) ? $data->location : null;
    $joined = $data->created_at;

    return new User($login, $name, $email, $location, $joined, $avatar);
  }

  /**
   * Get all the users and sort by given params
   * @param String $page
   * @param String $sortBy
   * @param String $language
   * @return Array $users, $pages
   */
  public function getAllUsers($page = null, $sortBy = null, $language = null, $value = 312, $perPage = 100) {
    $data = $this->github->getAllUsers($page, $sortBy, $language, $value, $perPage);

    foreach ($data["body"]->items as $key => $userData) {
      $login = $userData->login;
      $avatar = $userData->avatar_url;

      $users["users"][$key] = new User($login, null, null, null, null, $avatar);

    }

    $pages = $data["pages"];

    $firstPage = isset($pages["first"]) ? $pages["first"] : null;
    $prevPage = isset($pages["prev"]) ? $pages["prev"] : null;
    $nextPage = isset($pages["next"]) ? $pages["next"] : null;
    $lastPage = isset($pages["last"]) ? $pages["last"] : null;

    $users["pages"] = new Pages($firstPage, $prevPage, $nextPage, $lastPage);

    return $users;
  }

  /**
   * Get a single user
   * @param String $user
   * @return User user
   */
  public function getSingleUser($user) {

    $data = $this->github->getSingleUser($user);

    $login = $data->login;
    $avatar = $data->avatar_url;
    $name = isset($data->name) ? $data->name : null;
    $email = isset($data->email) ? $data->email : null;
    $location = isset($data->location) ? $data->location : null;
    $joined = $data->created_at;

    $user = new User($login, $name, $email, $location, $joined, $avatar);

    return $user;
  }

  /**
   * Get repos that a given user owns
   * @param String $user
   * @return Array $repos
   */
  public function getUserRepos($user) {
    $data = $this->github->getUserRepos($user);

    $repos = null;

    foreach ($data as $repoData) {
      $repoName = $repoData->name;
      $owner = $repoData->owner;

      $login = $repoData->owner->login;
      $avatar = $repoData->owner->avatar_url;

      $repos[] = new Repo($repoName, new User($login, null, null, null, null, $avatar));
    }

    return $repos;
  }

  /**
   * Get followers that a given user has
   * @param String $user
   * @return Array $followers
   */
  public function getUserFollowers($user) {
    $data = $this->github->getUserFollowers($user);

    $followers = null;

    foreach ($data as $followerData) {
      $login = $followerData->login;

      $followers[] = new Follower(new User($login, null, null, null, null, null));
    }

    return $followers;
  }

  /**
   * Get a given users activity
   * @param String $user
   * @return Array $events
   */
  public function getUserActivity($user) {
    $cacheKey = "events_{$user}";

    // Check caching, we will make a new request if the cache is empty.
    if (false === ($events = $this->memcached->get($cacheKey))) {
      $data = $this->github->getUserActivity($user);

      foreach ($data as $eventData) {
        $login = $eventData->actor->login;
        $avatar = $eventData->actor->avatar_url;

        $repoName = $eventData->repo->name;

        $type = $eventData->type;
        $payload = $eventData->payload;
        $createdAt = $eventData->created_at;

        $user = new User($login, null, null, null, null, $avatar);

        $events[] = new Activity($user, new Repo($repoName, $user), $type, $payload, $createdAt);
      }

      // Save the events to Memcached.
      $this->memcached->set($cacheKey, $events);
    }

    return $events;
  }

  public function searchUsers($query) {
    $data = $this->github->searchUsers($query);

    foreach ($data->items as $userData) {
      $login = $userData->login;
      $avatar = $userData->avatar_url;

      $users[] = new User($login, null, null, null, null, $avatar);
    }

    return $users;
  }

}