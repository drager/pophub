<?php

namespace PopHub\Model;

interface ServiceInterface {

  function authorize();

  function getLoggedInUser($token);

  function getAllUsers($page = null, $sortBy = null, $language = null);

  function getSingleUser($user);

  function getUserRepos($user);

  function getUserFollowers($user);

  function getUserActivity($user);
}