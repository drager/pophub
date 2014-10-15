<?php

return array(

  'DEBUG' => true,

  'ERROR_LOG' => "log/error.log",

  'GITHUB_CLIENT_ID' => getenv("POPHUB_GITHUB_CLIENT_ID"),

  'GITHUB_CLIENT_SECRET' => getenv("POPHUB_GITHUB_CLIENT_SECRET"),

  'GITHUB_CALLBACK_URL' => "http://localhost:9999/github-callback",

  'DB_CONNECTION' => "mysql:host=127.0.0.1;dbname=pophub",

  'DB_USER' => "appUser",

  'DB_PASSWORD' => "password"
);
