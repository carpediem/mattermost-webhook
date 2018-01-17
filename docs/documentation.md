---
layout: default
title: Documentation
---

## Introduction

All classes and functions are defined under the `Carpediem\Mattermost\Webhook` namespace.


In order to send notifications the library uses three (3) interfaces:

- `MessageInterface` a container for anything you want to send;
- `AttachmentInterface` a container for representing a mattermost attachment structure;
- `ClientInterface` a HTTP client;

These interfaces exposes only getter methods and for the `ClientInterface` a simple `ClientInterface::notify` method to send a `MessageInterface` object to a incoming webhook URL of a mattermost compliant service.

The package comes bundle with 2 value objects which are mutable and use the builder pattern to create the notification to be send. Properties type and possible values are fully explained on the [mattermost documentation website](https://docs.mattermost.com/developer/webhooks-incoming.html#simple-incoming-webhook)


If a property is invalid or an action can not be performed a `Carpediem\Mattermost\Webhook\Exception` is thrown by the library.

The shipped HTTP client uses `Guzzle` as its inner HTTP client but any client which can produce a `Psr\Http\Message\ResponseInterface` can be use to send the notification.

## Carpediem\Mattermost\Webhook\Message

This is the shipped implementation of `Carpediem\Mattermost\MessageInterface` as a mutable value object.

~~~php
<?php

namespace Carpediem\Mattermost\Webhook;

use Psr\Http\Message\UriInterface;

final class Message implements MessageInterface
{
    public function getText(): string;
    public function getChannel(): string;
    public function getUsername(): string;
    public function getIconUrl(): string;
    public function getAttachments(): iterable;
    public function toArray(): array;
    public function jsonSerialize():
    // The methods below are not part of the MessageInterface interface
    public function static fromArray(array $message): self;
    public function __construct(string $text): self;
    public function setText(string $text): self;
    public function setChannel(string $channel): self;
    public function setUsername(string $username): self;
    public function setIconUrl(string|UriInterface $icon_url): self;
    public function setAttachments(iterable $attachments): self;
    public function addAttachment(AttachmentInterface $attachment): self;
}
~~~

**Of note:**

- `MessageInterface::toArray` returns an complete `array` representation of the message including the array representation of each `Carpediem\Mattermost\Webhook\Attachment` object attached to the message.

- `MessageInterface::jsonSerialize` returns all non `null` and non empty properties of the message. The returned `array` represents the effective payload submitted to the webhook.

- `Message::addAttachment` will attach a new `Carpediem\Mattermost\Webhook\AttachmentInterface` object to the current message.

- `Message::setAttachments` will remove any previously attached `AttachmentInterface` objects prior to adding the new attachments.

<p class="message-warning"><code>Message::__construct</code> is added since version <code>1.2.0</code> and <code>2.2.0</code> to enforce the presence of at least the <code>text</code> property on any sent notification.</p>


## Carpediem\Mattermost\Attachment

This is the shipped implementation of `Carpediem\Mattermost\AttachmentInterface` as a mutable value object.

~~~php
<?php

namespace Carpediem\Mattermost\Webhook;

use Psr\Http\Message\UriInterface;

final class Attachment implements AttachmentInterface
{
    public function getFallback(): string;
    public function getPretext(): string;
    public function getText(): string;
    public function getColor(): string;
    public function getAuthorName(): string;
    public function getAuthorLink(): string;
    public function getAuthorIcon(): string;
    public function getTitle(): string;
    public function getTitleLink(): string;
    public function getFields(): Iterator;
    public function getImageUrl(): string;
    public function getThumbUrl(): string;
    public function toArray(): array;
    public function jsonSerialize();
    // The methods below are not part of the AttachmentInterface interface
    public function static fromArray(array $attachment): self;
    public function __construct(string $fallback): self;
    public function setFallback(string $fallback): self;
    public function setPretext(string $pretext): self;
    public function setText(string $text): self;
    public function setColor(string $color): self;
    public function setAuthor(
        string $author_name,
        string|UriInterface $author_link = '',
        string|UriInterface $author_icon = ''
    ): self;
    public function setTitle(string $title, string $title_link = ''): self;
    public function setFields(iterable $fields): self;
    public function addField(string $title, string $value, bool $short = true): self;
    public function setImageUrl(string|UriInterface $image_url): self;
    public function setThumbUrl(string|UriInterface $thumb_url): self;
}
~~~

**Of note:**

- `AttachmentInterface::toArray` returns an complete `array` representation of the attachment.
- `AttachmentInterface::jsonSerialize` returns all non `null` and non empty properties of the message. The returned `array` represents the effective data submitted to the webhook.
- `Attachment::addField` will attach a new field to the current object.
- `Attachment::setFields` will remove any previously attached field prior to adding the new submitted fields.

<p class="message-warning"><code>Attachment::__construct</code> is added since version <code>1.2.0</code> and <code>2.2.0</code> to enforce the presence of at least the <code>fallback</code> property on any <code>Attachment</code> object.</p>

## Carpediem\Mattermost\Client

This is the shipped implementation of `Carpediem\Mattermost\ClientInterface`. The `Client` uses Guzzle for HTTP transport.

~~~php
<?php

namespace Carpediem\Mattermost\Webhook;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class Client implements ClientInterface
{
    public function __construct(GuzzleClient $client, array $options = []);
    public function notify(string|UriInterface $url, MessageInterface $message): ResponseInterface;
}
~~~

## Example

Using the builder pattern you can easily recreate the [example from mattermost documentation](https://docs.mattermost.com/developer/message-attachments.html#example-message-attachment).

~~~php
<?php

require '/path/to/vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use Carpediem\Mattermost\Webhook\Attachment;
use Carpediem\Mattermost\Webhook\Client;
use Carpediem\Mattermost\Webhook\Message;

$attachment = (new Attachment('This is the fallback test for the attachment.'))
    ->setColor('#FF8000')
    ->setPretext('This is optional pretext that shows above the attachment.')
    ->setText('This is the text. **Finaly!**')
    ->setAuthor(
        'Mattermost',
        'http://www.mattermost.org/',
        'http://www.mattermost.org/wp-content/uploads/2016/04/icon_WS.png'
    )
    ->setTitle('Example attachment')
    ->setFields([
        ['Long field', 'Testing with a very long piece of text that will take up the whole width of the table. And then some more text to make it extra long.', false],
        ['Column one', 'Testing.', true],
        ['Column two', 'Testing.', true],
        ['Column one again', 'Testing.', true],
    ])
    ->setImageUrl('http://www.mattermost.org/wp-content/uploads/2016/03/logoHorizontal_WS.png')
;

$message = (new Message('This is a *test*.'))
    ->setChannel('alerts')
    ->setUsername('A Tester')
    ->setIconUrl('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif')
    ->addAttachment($attachment)
;

$mattermost = new Client(new GuzzleClient(['http_errors' => false]));
$response = $mattermost->notify('https://your_mattermost_webhook_url', $message);
if ($response->getStatusCode() < 400) {
    //the notification was successfully sent
} else {
    //something went wrong
}
//$response is a Psr7\Http\Message\ResponseInterface.
~~~