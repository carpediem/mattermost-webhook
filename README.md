# Mattermost PHP Webhook

This driver for Mattermost allows you to send message for [incoming webhooks](https://docs.mattermost.com/developer/webhooks-incoming.html).

This is a fork from [ThibaudDauce/mattermost-php](https://github.com/ThibaudDauce/mattermost-php)

System Requirements
--------

You need **PHP >= 5.6.0** but the latest stable version of PHP is recommended.

Installation
--------

```bash
$ composer require carpediem/mattermost-webhook
```

Build status
--------


| branch       | status | minimum PHP version |
| ------------ | ------ | ------------------- |
| master       | [![Build Status](https://travis-ci.org/carpediem/mattermost-webhook.svg?branch=master)](https://travis-ci.org/carpediem/mattermost-webhook/tree/master) | PHP 7.0 |
| 1.x          | [![Build Status](https://travis-ci.org/carpediem/mattermost-webhook.svg?branch=1.x)](https://github.com/carpediem/mattermost-webhook/tree/1.x) | PHP 5.6 |


Basic usage
--------

The code below will send a notification to a mattermost compliant service.

```php
<?php

require '/path/to/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Carpediem\Mattermost\Webhook\Client;
use Carpediem\Mattermost\Webhook\Message;

$mattermost = new Client(new GuzzleClient());
$message = new Message('This is a *test*.');
$response = $mattermost->notify('https://your_mattermost_webhook_url', $message);

//$response is a Psr7\Http\Message\ResponseInterface.
```

Documentation
--------

Full documentation can be found at [carpediem.github.io](//carpediem.github.io/mattermost-webhook).


Contributing
-------

Contributions are welcome and will be fully credited. Please see [CONTRIBUTING](.github/CONTRIBUTING.md)for details.

Testing
-------

`Mattermost Webhook` has a [PHPUnit](https://phpunit.de) test suite and a coding style compliance test suite using [PHP CS Fixer](http://cs.sensiolabs.org/). To run the tests, run the following command from the project folder.

``` bash
$ composer test
```

Security
-------

If you discover any security related issues, please email dev@carpediem.fr instead of using the issue tracker.

Credits
-------

- [carpediem](https://github.com/carpediem)
- [ThibaudDauce/mattermost-php](https://github.com/ThibaudDauce/mattermost-php)
- [All Contributors](https://github.com/carpediem/mattermost-webhook/contributors)

License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.