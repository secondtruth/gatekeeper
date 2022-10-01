# Gatekeeper

[![Build Status](https://img.shields.io/travis/secondtruth/gatekeeper.svg)](https://travis-ci.org/secondtruth/gatekeeper)
[![Scrutinizer](http://img.shields.io/scrutinizer/g/secondtruth/gatekeeper.svg)](https://scrutinizer-ci.com/g/secondtruth/gatekeeper)
[![Coverage](http://img.shields.io/scrutinizer/coverage/g/secondtruth/gatekeeper.svg)](https://scrutinizer-ci.com/g/secondtruth/gatekeeper)
[![License](http://img.shields.io/packagist/l/secondtruth/gatekeeper.svg)](https://packagist.org/packages/secondtruth/gatekeeper)

The Gatekeeper library protects websites from spam and other attacks. It prevents bad bots from delivering their junk, and in many cases,
from ever reading your site in the first place.


## Description

Welcome to a whole new way of keeping your service, forum, wiki or content management system free of spam and other attacks.
Gatekeeper is a PHP-based solution for blocking spam and the robots which deliver it. This keeps your site's load down,
makes your site logs cleaner, and can help prevent denial of service conditions caused by spammers.

Gatekeeper also transcends other anti-spam solutions by working in a completely different, unique way. Instead of merely
looking at the content of potential spam, Gatekeeper analyzes the delivery method as well as the software the spammer
is using. In this way, Gatekeeper can stop spam attacks even when nobody has ever seen the particular spam before.

Gatekeeper is designed to work alongside existing spam prevention services to increase their effectiveness and efficiency.
Whenever possible, you should run it in combination with a more traditional spam prevention service.

The library is inspired by the **Bad Behavior** anti-spam system by **Michael Hampton**.


## Usage

Include the vendor autoloader and use the classes:

```php
namespace Acme\MyApplication;

use Secondtruth\Gatekeeper\Screener;
use Secondtruth\Gatekeeper\Gatekeeper;
use Secondtruth\Gatekeeper\Listing\IPList;
use Secondtruth\Gatekeeper\Check\IPBlacklistCheck;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';
```

Create the `Check` object(s) you want to use:

```php
$blacklist = new IPList(['127.0.0.2', '127.0.0.3/32']);
$check = new IPBlacklistCheck($blacklist);
```

Create a `Screener` object and add the checks to it:

```php
$screener = new Screener();
$screener->setWhitelist(new IPList('127.0.0.1'));
$screener->addCheck($check);
```

Create a `Gatekeeper` object and run it using the screener:

```php
$request = Request::createFromGlobals();

$gatekeeper = new Gatekeeper();
$gatekeeper->run($request, $screener);
```


## Installation

### Install via Composer

[Install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) if you don't already have it present on your system.

To install the library, run the following command and you will get the latest development version:

    $ php composer.phar require secondtruth/gatekeeper:dev-master


## Requirements

* You must have at least PHP version 8.1 installed on your system.


## Author, Credits and License

This project was created by [Christian Neff](https://www.secondtruth.de) ([@secondtruth](https://github.com/secondtruth))
and is licensed under the [MIT License](LICENSE.md).

Based on the work of:

* Michael Hampton

Thanks to [all other Contributors](https://github.com/secondtruth/wumbo/graphs/contributors)!
