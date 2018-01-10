<?php

namespace Carpediem\Mattermost\Test;

use Carpediem\Mattermost\Attachment;
use Carpediem\Mattermost\Exception;
use Carpediem\Mattermost\Message;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Carpediem\Mattermost\Message
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
            ->text('This is a *test*.')
            ->channel('tests')
            ->username('A Tester')
            ->iconUrl('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif')
            ->attachment(function (Attachment $attachment) {
                $attachment->success();
            })
        ;
        $this->assertNotEmpty($message->toArray());
        $this->assertNotEmpty($message->jsonSerialize());
    }

    public function testBuilderThrowsExceptionWithNonStringableValue()
    {
        $this->expectException(Exception::class);
        (new Message())->text(date_create());
    }

    public function testBuilderThrowsExceptionWithInvalidUri()
    {
        $this->expectException(Exception::class);
        (new Message())->iconUrl('//github.com');
    }

    public function testBuilderThrowsExceptionWithInvalidAttachment()
    {
        $this->expectException(Exception::class);
        (new Message())->attachment('foobar');
    }

    public function testMutability()
    {
        $message = new Message();
        $message->text('Coucou it\'s me');
        $message->attachments([new Attachment(), new Attachment()]);
        $this->assertSame('Coucou it\'s me', $message->toArray()['text']);
        $message->text('Overwritten info');
        $this->assertSame('Overwritten info', $message->toArray()['text']);
    }
}
