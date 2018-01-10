# Mattermost PHP Driver

This driver for Mattermost allows you to send message for [incoming webhooks](https://docs.mattermost.com/developer/webhooks-incoming.html).

This is a fork from [ThibaudDauce/mattermost-php
](https://github.com/ThibaudDauce/mattermost-php)

## System Requirements

You need **PHP >= 5.6.0** but the latest stable version of PHP is recommended.

## Installation

```bash
$ composer require carpediem/mattermost-php
```

## Basic usage

The code above will simulate the roll of two six-sided die

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Carpediem\Mattermost\Client;
use Carpediem\Mattermost\Message;
use Carpediem\Mattermost\Attachment;

$mattermost = new Client(new GuzzleClient());
$message = (new Message())->text('This is a *test*.')
$response = $mattermost->send('https://your_mattermost_webhook_url', $message, ['http_errors' => false]);

//$response is a Psr7\Http\Message\ResponseInterface.
```

## Advanced usage

Use mattermost documentation to enables more options for the `Message` and or the `Attachement` objects.

```php
<?php

require dirname(__DIR__).'/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Carpediem\Mattermost\Attachment;
use Carpediem\Mattermost\Client;
use Carpediem\Mattermost\Message;

$message = (new Message())
    ->text('This is a *test*.')
    ->channel('alerts')
    ->username('A Tester')
    ->iconUrl('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif')
    ->attachment(function (Attachment $attachment) {
        $attachment
            ->fallback('This is the fallback test for the attachment.')
                ->success()
                ->pretext('This is optional pretext that shows above the attachment.')
                ->text('This is the text. **Finaly!**')
                ->authorName('Mattermost')
                ->authorIcon('http://www.mattermost.org/wp-content/uploads/2016/04/icon_WS.png')
                ->authorLink('http://www.mattermost.org/')
                ->title('Example attachment')
                ->fields([
                    ['Long field', 'Testing with a very long piece of text that will take up the whole width of the table. And then some more text to make it extra long.', false],
                    ['Column one', 'Testing.', true],
                    ['Column two', 'Testing.', true],
                    ['Column one again', 'Testing.', true],
                ])
                ->imageUrl('http://www.mattermost.org/wp-content/uploads/2016/03/logoHorizontal_WS.png')
        ;
    })
;

$mattermost = new Client(new GuzzleClient());
$response = $mattermost->send('https://your_mattermost_webhook_url', $message, ['http_errors' => false]);

//$response is a Psr7\Http\Message\ResponseInterface.
```