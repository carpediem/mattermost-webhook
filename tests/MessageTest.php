<?php

namespace Carpediem\Mattermost\Webhook\Test;

use Carpediem\Mattermost\Webhook\Attachment;
use Carpediem\Mattermost\Webhook\Exception;
use Carpediem\Mattermost\Webhook\Message;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @coversDefaultClass Carpediem\Mattermost\Webhook\Message
 */
final class MessageTest extends TestCase
{
    public function testAttachmentState()
    {
        $message = new Message();
        $this->assertEmpty($message->jsonSerialize());
        $this->assertNotEmpty($message->toArray());
    }

    public function testBuilder()
    {
        $message = (new Message())
            ->setText('This is a *test*.')
            ->setChannel('tests')
            ->setUsername('A Tester')
            ->setIconUrl('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif')
            ->addAttachment(new Attachment())
        ;
        $this->assertNotEmpty($message->toArray());
        $this->assertNotEmpty($message->jsonSerialize());
        $this->assertSame('This is a *test*.', $message->getText());
        $this->assertSame('tests', $message->getChannel());
        $this->assertSame('A Tester', $message->getUsername());
        $this->assertSame('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif', $message->getIconUrl());
        $this->assertContainsOnlyInstancesOf(Attachment::class, $message->getAttachments());
    }

    public function testBuilderThrowsExceptionWithSetAttachments()
    {
        $this->expectException(TypeError::class);
        (new Message())->setAttachments((object) ['foo', 'bar']);
    }

    public function testBuilderThrowsExceptionWithInvalidUri()
    {
        $this->expectException(Exception::class);
        (new Message())->setIconUrl('//github.com');
    }

    public function testBuilderThrowsExceptionWithInvalidAttachment()
    {
        $this->expectException(TypeError::class);
        (new Message())->addAttachment('foobar');
    }

    public function testMutability()
    {
        $message = new Message();
        $message->setText('Coucou it\'s me');
        $message->setAttachments([new Attachment(), new Attachment()]);
        $this->assertSame('Coucou it\'s me', $message->getText());
        $message->setText('Overwritten info');
        $this->assertSame('Overwritten info', $message->getText());
    }
}
