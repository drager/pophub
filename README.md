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

If `composer install` fails it may depend on the --dev specific dependencies.
They use **cURL** so enable it or skip those dependencies running
`composer install --no-dev` instead.

**KEEP IN MIND** if you run using `composer install --no-dev` you cannot
run tests.

**Memcached** and **MySQL** is a dependency as well.

## Local version

### Keep in mind
Keep in mind that the login **WONT** work without a valid `GITHUB_CLIENT_ID` and `GITHUB_CLIENT_SECRET`.

### Install dependencies

First of get [composer](https://getcomposer.org/)

Then run `composer install` and all the dependencies will be installed.

### Windows

Windows can have trouble setting up memcached...

First of all you need memcached for windows, there's a guide [here](http://zurmo.org/wiki/installing-memcache-on-windows). In the section "Installing PHP Extension" get a file [here](http://windows.php.net/downloads/pecl/releases/memcache/3.0.8/) instead, and choose your php version.

If you can't get memcache**d** (Notice the d at the end). but can install
memcache you will need to change the line [here](https://github.com/jh222xk/pophub/blob/master/kagu/src/Cache/Memcached.php#L15)
from `new \Memcached()` to `new \Memcache()`.

### Ubuntu

There's a great guide on how you setup memcached [here](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-memcache-on-ubuntu-12-04).

### If you can't install memcached for some reason

If you can't install memcached there's a branch for you without it
[here](https://github.com/jh222xk/pophub/tree/without_memcached).

## Database

### Settings

Set up a database with the name specified in `path/to/pophub/app/config/app.php`
in `DB_CONNECTION =>`, a database username in `DB_USER =>` and a database password
in `DB_PASSWORD =>`

### Create table using pop command

#### Windows

Run the command line script located at `path/to/pophub` called `pop`.

Run it like this: `php pop create`

#### Linux/OSX

First of, set `chmod +x` on the script located at `path/to/pophub` called `pop`.

Then run it like this: `php pop create`

### Create table manually
Here is the table needed.
```sql
CREATE TABLE followers (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
user VARCHAR(255) NOT NULL,
owner VARCHAR(255) NOT NULL,
created_at DATETIME NOT NULL);
```

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


### Travis
Continuous Integration using Travis CI can be found at 
https://travis-ci.org/jh222xk/pophub

### Coveralls
Code coverage using Coveralls can be found at 
https://coveralls.io/r/jh222xk/pophub