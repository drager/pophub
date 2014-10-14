PopHub
======
Follow the most popular users on Github.

![Travis CI](https://travis-ci.org/jh222xk/pophub.svg?branch=master)

## Local version

### How to get started

Locate to `path/to/pophub/app/`then just run PHP's built-in server using the command: `php -S localhost:9999` and the application will be served.


## Tests

The tests for the main applicaiton are located at `path/to/pophub/app/tests/`

Tests for "helpers" are located at `path/to/pophub/kagu/tests/`

### Run tests

To run the tests just locate to `path/to/pophub/` and type `vendor/bin/phpunit app/tests/`

and for the "helpers" type `vendor/bin/phpunit kagu/tests/`

### Test/Code coverage

To get code coverage simply locate to `path/to/pophub/` and type `vendor/bin/phpunit --coverage-html ./report app/tests/` and PHPUnit will generate a report for you.