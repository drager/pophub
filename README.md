PopHub
======
Follow the most popular users on Github.

[![Build Status](https://travis-ci.org/jh222xk/pophub.svg?branch=master)](https://travis-ci.org/jh222xk/pophub)

[![Coverage Status](https://img.shields.io/coveralls/jh222xk/pophub.svg)](https://coveralls.io/r/jh222xk/pophub)

The application is published [here](http://pophub.jesperh.se/).

## Dependencies

PHP Version 5.4, 5.5, 5.6

Check [composer.json](https://github.com/jh222xk/pophub/blob/master/composer.json) for dependencies needed.

Those components in `composer.json` certainly has some dependencies as well. (Hopefully those will be installed without problems).

Memcached is a dependency as well.

## Local version

### Keep in mind
Keep in mind that the login **WONT** work without a valid `GITHUB_CLIENT_ID` and `GITHUB_CLIENT_SECRET`.

### Install dependencies

First of get [composer](https://getcomposer.org/)

Then run `composer install` and all the dependencies will be installed.

### Windows

Windows can have trouble setting up memcached...

First of all you need memcached for windows, there's a guide [here](http://zurmo.org/wiki/installing-memcache-on-windows). In the section "Installing PHP Extension" get a file [here](http://windows.php.net/downloads/pecl/releases/memcache/3.0.8/) instead, and choose your php version.

### Ubuntu

There's a great guide on how you setup memcached [here](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-memcache-on-ubuntu-12-04).


### Run the application

Locate to `path/to/pophub/app/`then just run PHP's built-in server using the command: `php -S localhost:9999` and the application will be served.

## Tests

The tests for the main applicaiton are located at `path/to/pophub/app/tests/`

Tests for "helpers" are located at `path/to/pophub/kagu/tests/`

### Run tests

To run the tests just locate to `path/to/pophub/` and type `vendor/bin/phpunit app/tests/`

and for the "helpers" type `vendor/bin/phpunit kagu/tests/`

### Test/Code coverage

To get code coverage simply locate to `path/to/pophub/` and type `vendor/bin/phpunit --coverage-html ./report app/tests/` and PHPUnit will generate a report for you.