<?php

namespace Carpediem\Mattermost\Webhook\Test;

use Carpediem\Mattermost\Webhook\Attachment;
use Carpediem\Mattermost\Webhook\Exception;
use Carpediem\Mattermost\Webhook\Message;
use GuzzleHttp\Psr7;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @coversDefaultClass Carpediem\Mattermost\Webhook\Message
 */
final class MessageTest extends TestCase
{
    public function testAttachmentState()
    {
        $message = new Message('This is a *test*.');
        $this->assertNotEmpty($message->jsonSerialize());
        $this->assertNotEmpty($message->toArray());
    }

    public function testBuilder()
    {
        $message = (new Message('This is a *test*.'))
            ->setChannel('tests')
            ->setUsername('A Tester')
            ->setIconUrl(Psr7\uri_for('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif'))
            ->addAttachment(new Attachment('fallback'))
        ;
        $this->assertNotEmpty($message->toArray());
        $this->assertNotEmpty($message->jsonSerialize());
        $this->assertSame('This is a *test*.', $message->getText());
        $this->assertSame('tests', $message->getChannel());
        $this->assertSame('A Tester', $message->getUsername());
        $this->assertSame('https://upload.wikimedia.org/wikipedia/fr/f/f6/Phpunit-logo.gif', $message->getIconUrl());
        $this->assertContainsOnlyInstancesOf(Attachment::class, $message->getAttachments());
    }

    public function testBuilderRequiresAText()
    {
        $this->expectException(Exception::class);
        new Message();
    }

    public function testBuilderThrowsExceptionWithSetAttachments()
    {
        $this->expectException(TypeError::class);
        (new Message('This is a *test*.'))->setAttachments((object) ['foo', 'bar']);
    }

    /**
     * @dataProvider invalidUriProvider
     * @param mixed $uri
     */
    public function testBuilderThrowsExceptionWithInvalidUri($uri)
    {
        $this->expectException(Exception::class);
        (new Message('This is a *test*.'))->setIconUrl($uri);
    }

    public function invalidUriProvider()
    {
        return [
            'non absolute URI' => ['//github.com'],
            'non http/https URI' => ['ftp://github.com'],
            'wrong type' => [date_create()],
            'non absolute UriInterface' => [Psr7\uri_for('//github.com')],
            'non http/https UriInterface' => [Psr7\uri_for('ftp://github.com')],
        ];
    }

    public function testBuilderThrowsExceptionWithInvalidAttachment()
    {
        $this->expectException(TypeError::class);
        (new Message('This is a *test*.'))->addAttachment('foobar');
    }

    public function testMutability()
    {
        $message = new Message('Coucou it\'s me');
        $message->setAttachments([new Attachment('fallback'), new Attachment('fallback')]);
        $this->assertSame('Coucou it\'s me', $message->getText());
        $message->setText('Overwritten info');
        $this->assertSame('Overwritten info', $message->getText());
    }

    public function testSetState()
    {
        $message = new Message('Coucou it\'s me');
        $message->setAttachments([new Attachment('fallback'), new Attachment('fallback')]);
        $generatedMessage = eval('return '.var_export($message, true).';');
        $this->assertEquals($message, $generatedMessage);
    }

    public function testCreateFromArray()
    {
        $message = new Message('Coucou it\'s me');
        $message->setAttachments([new Attachment('fallback'), new Attachment('fallback')]);
        $this->assertEquals($message, Message::fromArray($message->toArray()));
    }
}
