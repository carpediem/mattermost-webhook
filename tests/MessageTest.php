<?php

namespace Carpediem\Mattermost\Webhook\Test;

use Carpediem\Mattermost\Webhook\Attachment;
use Carpediem\Mattermost\Webhook\Exception;
use Carpediem\Mattermost\Webhook\Message;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Carpediem\Mattermost\WebhookMessage
 */
final class MessageTest extends TestCase
{
    public function testAttachmentState()
    {
        $message = new Message('default text');
        $this->assertNotEmpty($message->jsonSerialize());
        $this->assertNotEmpty($message->toArray());
    }
    public function testBuilder()
    {
        $message = (new Message('default text'))
            ->setText('This is a *test*.')
            ->setChannel('tests')
            ->setUsername('A Tester')
            ->setIconUrl('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif')
            ->addAttachment(new Attachment('fallback text'))
        ;
        $this->assertNotEmpty($message->toArray());
        $this->assertNotEmpty($message->jsonSerialize());
        $this->assertSame('This is a *test*.', $message->getText());
        $this->assertSame('tests', $message->getChannel());
        $this->assertSame('A Tester', $message->getUsername());
        $this->assertSame('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif', $message->getIconUrl());
        $this->assertContainsOnlyInstancesOf(Attachment::class, $message->getAttachments());
    }

    public function testBuilderThrowsExceptionWithNonStringableValue()
    {
        $this->expectException(Exception::class);
        new Message(date_create());
    }

    public function testBuilderThrowsExceptionWithEmptyString()
    {
        $this->expectException(Exception::class);
        Message::fromArray([]);
    }

    public function testBuilderThrowsExceptionWithInvalidUri()
    {
        $this->expectException(Exception::class);
        (new Message('default text'))->setIconUrl('//github.com');
    }

    public function testBuilderThrowsExceptionWithSetAttachments()
    {
        $this->expectException(Exception::class);
        (new Message('default text'))->setAttachments((object) ['foo', 'bar']);
    }

    public function testMutability()
    {
        $message = new Message('Coucou it\'s me');
        $message->setAttachments([new Attachment('fallback text'), new Attachment('fallback text')]);
        $this->assertSame('Coucou it\'s me', $message->getText());
        $message->setText('Overwritten info');
        $this->assertSame('Overwritten info', $message->getText());
    }

    public function testSetState()
    {
        $message = new Message('Coucou it\'s me');
        $message->setAttachments([new Attachment('fallback text'), new Attachment('fallback text')]);

        $generatedMessage = eval('return '.var_export($message, true).';');
        $this->assertEquals($message, $generatedMessage);
    }

    public function testCreateFromArray()
    {
        $message = new Message('Coucou it\'s me');
        $message->setAttachments([new Attachment('fallback text'), new Attachment('fallback text')]);
        $this->assertEquals($message, Message::fromArray($message->toArray()));
    }
}
