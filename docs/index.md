---
layout: default
title: Mattermost - Webhook - A PHP mattermost webhook incoming notification system
---

Introduction [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/carpediem/mattermost-webhook/blob/master/LICENSE)
------

`carpediem/mattermost-webhook` is a simple library to ease sending [incoming webhooks](https://docs.mattermost.com/developer/webhooks-incoming.html) to Mattermost compliant services.

Highlights
-------

* Simple API
* Fully documented
* Fully Unit tested
* Framework-agnostic

Build status
-------

| branch       | status | minimum PHP version |
| ------------ | ------ | ------------------- |
| master       | [![Build Status](https://travis-ci.org/carpediem/mattermost-webhook.svg?branch=master)](https://travis-ci.org/carpediem/mattermost-webhook/tree/master) | PHP 7.0 |
| 1.x          | [![Build Status](https://travis-ci.org/carpediem/mattermost-webhook.svg?branch=1.x)](https://github.com/carpediem/mattermost-webhook/tree/1.x) | PHP 5.6 |

Basic usage
-------

The code below will send a mattermost notification

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

Credits
-------

This package is a fork from [ThibaudDauce/mattermost-php ](https://github.com/ThibaudDauce/mattermost-php)
improved by
[Carpediem](//carpediem.github.io)

License
-------

The MIT License (MIT). Please see [LICENSE] for more information.

[LICENSE]: https://github.com/carpediem/mattermost-webhook/blob/master/LICENSE