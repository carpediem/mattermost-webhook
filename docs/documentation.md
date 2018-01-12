---
layout: default
title: Documentation
---

## Introduction

In order to send notifications the library uses two DTOs:

- `Carpediem\Mattermost\Message` a container for anything you want to send;
- `Carpediem\Mattermost\Attachment` a container to represent a mattermost attachment structure;

and an HTTP client which is a simple wrapper around Guzzle.

- `Carpediem\Mattermost\Client`;

Both DTOs objects are mutable and uses the builder pattern to create the notification to be send. If a property is invalid a `Carpediem\Mattermost\Exception` or a `Psr\Http\Message\InvalidArgumentException` may be thrown by the library.

Properties type and possible value are fully explained on the [mattermost documentation website](https://docs.mattermost.com/developer/webhooks-incoming.html#simple-incoming-webhook)

## Carpediem\Mattermost\Message

~~~php
<?php

namespace Carpediem\Mattermost\Webhook;

use Psr\Http\Message\UriInterface;

final class Message implements JsonSerializable
{
    public function addAttachment(Attachment $attachment): self;
    public function getAttachments(): iterable;
    public function getChannel(): string;
    public function getIconUrl(): string;
    public function getText(): string;
    public function getUsername(): string;
    public function setAttachments(iterable $attachments): self;
    public function setChannel(string $channel): self;
    public function setIconUrl(string|UriInterface $icon_url): self;
    public function setText(string $text): self;
    public function setUsername(string $username): self;
    public function toArray(): array;
}
~~~

**Of note:**

- `Message::toArray` returns an complete `array` representation of the message including the array representation of each `Carpediem\Mattermost\Webhook\Attachment` object attached to the message.

- `Message::jsonSerialize` returns all non `null` and non empty properties of the message. The returned `array` represents the effective payload submitted to the webhook.

- `Message::addAttachment` will attach a new `Carpediem\Mattermost\Webhook\Attachment` object to the current message.

- `Message::setAttachments` will remove any previously attached `Carpediem\Mattermost\Webhook\Attachment`objects prior to adding the new attachments.

## Carpediem\Mattermost\Attachment

~~~php
<?php

namespace Carpediem\Mattermost\Webhook;

use Psr\Http\Message\UriInterface;

final class Attachment implements JsonSerializable
{

    public function addField(string $title, string $value, bool $short = true): self;
    public function getAuthorName(): string;
    public function getAuthorLink(): string;
    public function getAuthorIcon(): string;
    public function getColor(): string;
    public function getFallback(): string;
    public function getFields(): Iterator;
    public function getImageUrl(): string;
    public function getPretext(): string;
    public function getText(): string;
    public function getThumbUrl(): string;
    public function getTitle(): string;
    public function getTitleLink(): ?string;
    public function setAuthorName(string $author_name): self;
    public function setAuthorLink(string|UriInterface$author_link): self;
    public function setAuthorIcon(string|UriInterface$author_icon): self;
    public function setColor(string $color): self;
    public function setFallback(string $fallback): self;
    public function setFields(iterable $fields): self;
    public function setImageUrl(string|UriInterface$image_url): self;
    public function setPretext(string $pretext): self;
    public function setText(string $text): self;
    public function setThumbUrl(string|UriInterface$thumb_url): self;
    public function setTitle(string $title, string $title_link = null): self;
    public function toArray(): array;
}
~~~

**Of note:**

- `Attachment::toArray` returns an complete `array` representation of the attachment.
- `Attachment::jsonSerialize` returns all non `null` and non empty properties of the message. The returned `array` represents the effective data submitted to the webhook.
- `Attachment::addField` will attach a new field to the current object.
- `Attachment::setFields` will remove any previously attached field prior to adding the new submitted fields.


## Carpediem\Mattermost\Client

~~~php
<?php

namespace Carpediem\Mattermost\Webhook;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

final class Client
{

    public function __construct(GuzzleClient $client);
    public function send($url, Message $message, array $options = []): ResponseInterface;
}
~~~

**Of note:**

- `Client::send` optional `$options` parameter takes [Guzzle additionals](http://docs.guzzlephp.org/en/stable/request-options.html) options.

- depending on how you setup your errors, the `Client::send` may throw `GuzzleHttp\Exception\RequestException` or `GuzzleHttp\Exception\TransferException`


## Example

Using the builder pattern you can easily recreate the [example from mattermost documentation](https://docs.mattermost.com/developer/message-attachments.html#example-message-attachment).

~~~php
<?php

require dirname(__DIR__).'/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Carpediem\Mattermost\Webhook\Attachment;
use Carpediem\Mattermost\Webhook\Client;
use Carpediem\Mattermost\Webhook\Message;

$attachment = (new Attachment())
    ->setFallback('This is the fallback test for the attachment.')
    >setColor('#FF8000')
    ->setPretext('This is optional pretext that shows above the attachment.')
    ->setText('This is the text. **Finaly!**')
    ->setAuthorName('Mattermost')
    ->setAuthorIcon('http://www.mattermost.org/wp-content/uploads/2016/04/icon_WS.png')
    ->setAuthorLink('http://www.mattermost.org/')
    ->setTitle('Example attachment')
    ->setFields([
        ['Long field', 'Testing with a very long piece of text that will take up the whole width of the table. And then some more text to make it extra long.', false],
        ['Column one', 'Testing.', true],
        ['Column two', 'Testing.', true],
        ['Column one again', 'Testing.', true],
    ])
    ->setImageUrl('http://www.mattermost.org/wp-content/uploads/2016/03/logoHorizontal_WS.png')
;

$message = (new Message())
    ->setText('This is a *test*.')
    ->setChannel('alerts')
    ->setUsername('A Tester')
    ->setIconUrl('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif')
    ->addAttachment($attachment)
;

$mattermost = new Client(new GuzzleClient());
$response = $mattermost->send(
	'https://your_mattermost_webhook_url',
	$message,
	['http_errors' => false]
);

//$response is a Psr7\Http\Message\ResponseInterface.
~~~