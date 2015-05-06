FlameCore Gatekeeper
====================

[![Build Status](https://img.shields.io/travis/FlameCore/Gatekeeper.svg)](https://travis-ci.org/FlameCore/Gatekeeper)
[![Scrutinizer](http://img.shields.io/scrutinizer/g/FlameCore/Gatekeeper.svg)](https://scrutinizer-ci.com/g/FlameCore/Gatekeeper)
[![Coverage](http://img.shields.io/codeclimate/coverage/github/FlameCore/Gatekeeper.svg)](https://codeclimate.com/github/FlameCore/Gatekeeper/coverage)
[![License](http://img.shields.io/packagist/l/flamecore/gatekeeper.svg)](https://packagist.org/packages/flamecore/gatekeeper)

This library prevents spammers from delivering their junk, and in many cases, from ever reading your site in the first place.


Description
-----------

Welcome to a whole new way of keeping your service, forum, wiki or content management system free of spam and other attacks.
Gatekeeper is a PHP-based solution for blocking spam and the robots which deliver it. This keeps your site's load down,
makes your site logs cleaner, and can help prevent denial of service conditions caused by spammers.

Gatekeeper is designed to work alongside existing spam prevention services to increase their effectiveness and efficiency.
Whenever possible, you should run it in combination with a more traditional spam prevention service.

The library is inspired by the [Bad Behavior](http://bad-behavior.ioerror.us/) system.


Installation
------------

### Install via Composer

Create a file called `composer.json` in your project directory and put the following into it:

```
{
    "require": {
        "flamecore/gatekeeper": "dev-master"
    }
}
```

[Install Composer](https://getcomposer.org/doc/00-intro.md#installation-nix) if you don't already have it present on your system:

    $ curl -sS https://getcomposer.org/installer | php

Use Composer to [download the vendor libraries](https://getcomposer.org/doc/00-intro.md#using-composer) and generate the vendor/autoload.php file:

    $ php composer.phar install

Include the vendor autoloader and use the classes:

```php
namespace Acme\MyApplication;

use FlameCore\Gatekeeper\Gatekeeper;
use FlameCore\Gatekeeper\Screener;

require 'vendor/autoload.php';
```


Requirements
------------

* You must have at least PHP version 5.4 installed on your system.


Contributors
------------

If you want to contribute, please see the [CONTRIBUTING](CONTRIBUTING.md) file first.

Thanks to the contributors:

* Christian Neff (secondtruth)
