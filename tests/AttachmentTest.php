<?php

namespace Carpediem\Mattermost\Webhook\Test;

use Carpediem\Mattermost\Webhook\Attachment;
use Carpediem\Mattermost\Webhook\Exception;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Carpediem\Mattermost\WebhookAttachment
 */
final class AttachmentTest extends TestCase
{
    public function testAttachmentState()
    {
        $attachment = new Attachment();
        $this->assertEmpty($attachment->jsonSerialize());
        $this->assertNotEmpty($attachment->toArray());
    }

    public function testBuilder()
    {
        $attachment = (new Attachment())
            ->setFallback('This is the fallback test for the attachment.')
            ->setPretext('This is optional pretext that shows above the attachment.')
            ->setText('This is the text. **Finaly!**')
            ->setAuthorName('Mattermost')
            ->setAuthorIcon('http://www.mattermost.org/wp-content/uploads/2016/04/icon_WS.png')
            ->setAuthorLink('http://www.mattermost.org/')
            ->setTitle('Example attachment', 'http://www.example.com')
            ->setFields([
                ['Long field', 'Testing with a very long piece of text that will take up the whole width of the table. And then some more text to make it extra long.', false],
                ['Column one', 'Testing.', true],
                ['Column two', 'Testing.', true],
                ['Column one again', 'Testing.', true],
            ])
            ->setColor('#ff3300')
            ->setImageUrl('http://www.mattermost.org/wp-content/uploads/2016/03/logoHorizontal_WS.png')
        ;
        $this->assertNotEmpty($attachment->toArray());
        $this->assertNotEmpty($attachment->jsonSerialize());
        $this->assertSame('#ff3300', $attachment->getColor());
        $this->assertSame('This is the fallback test for the attachment.', $attachment->getFallback());
        $this->assertSame('This is optional pretext that shows above the attachment.', $attachment->getPretext());
        $this->assertSame('This is the text. **Finaly!**', $attachment->getText());
        $this->assertSame('Mattermost', $attachment->getAuthorName());
        $this->assertSame('http://www.mattermost.org/wp-content/uploads/2016/04/icon_WS.png', $attachment->getAuthorIcon());
        $this->assertSame('http://www.mattermost.org/', $attachment->getAuthorLink());
        $this->assertSame('Example attachment', $attachment->getTitle());
        $this->assertSame('http://www.example.com', $attachment->getTitleLink());
        $this->assertSame('http://www.mattermost.org/wp-content/uploads/2016/03/logoHorizontal_WS.png', $attachment->getImageUrl());
        $this->assertCount(4, $attachment->getFields());
    }

    public function testBuilderThrowsExceptionWithNonStringableValue()
    {
        $this->expectException(Exception::class);
        (new Attachment())->setFallback(date_create());
    }

    public function testBuilderThrowsExceptionWithInvalidUri()
    {
        $this->expectException(Exception::class);
        (new Attachment())->setThumbUrl('wss://github.com');
    }

    public function testBuilderThrowsExceptionWithSetFields()
    {
        $this->expectException(Exception::class);
        (new Attachment())->setFields((object) ['foo', 'bar']);
    }

    public function testMutability()
    {
        $attachment = new Attachment();
        $attachment
            ->setThumbUrl('https://example.com/photo.png')
            ->setTitle('Example attachment', 'http://docs.mattermost.com/developer/message-attachments.html')
        ;

        $this->assertSame('Example attachment', $attachment->getTitle());
        $this->assertSame('https://example.com/photo.png', $attachment->getThumbUrl());

        $attachment->setTitle('Overwritten info');
        $this->assertSame('Overwritten info', $attachment->getTitle());
        $this->assertSame('https://example.com/photo.png', $attachment->getThumbUrl());
    }
}
