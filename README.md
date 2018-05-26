# Session class

[![Build Status](https://travis-ci.org/mixset/Session-management-class.png)](https://travis-ci.org/mixset/Session-management-class)

Session class provides easy way to manage sessions using custom PHP functions on your website.
 
## How to use

To start using class, you need to include this class to your project, eg. use `include`,`require` or `__autoload()` function. 
To run tests locally, install dependencies first by `composer install`.
It will install latest `phpunit` for php 5.6 version.

##  Contributing

If you have any idea how application can be improved, please create new issue with detailed description of your idea. Thank you :)

## Changelog

[16-08-2013] - v1.0
- First version has been released 

[01.12.2015] - v1.1
- Added namespace to not do conflict 
- Some methods have been improved

[15.02.2017] - v1.2
- Code optimization
- Secure method added
- Removed `$multi` param from `set` method

[30.03.2018]
- Better directory structure
- Added tests
- Added separate file for demo
- Created custom exception
- Improved documentation of methods

## ToDo

- Add management of session lifetime
- Add class loading by Composer