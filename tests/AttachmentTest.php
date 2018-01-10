<?php

namespace Carpediem\Mattermost\Test;

use Carpediem\Mattermost\Attachment;
use Carpediem\Mattermost\Exception;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @coversDefaultClass Carpediem\Mattermost\Attachment
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
        $this->assertNotEmpty($attachment->toArray());
        $this->assertNotEmpty($attachment->jsonSerialize());
    }

    public function testBuilderThrowsExceptionWithNonStringableValue()
    {
        $this->expectException(TypeError::class);
        (new Attachment())->error()->fallback(date_create());
    }

    public function testBuilderThrowsExceptionWithInvalidUri()
    {
        $this->expectException(Exception::class);
        (new Attachment())->thumbUrl('wss://github.com');
    }

    public function testMutability()
    {
        $attachment = new Attachment();
        $attachment
            ->info()
            ->thumbUrl('https://example.com/photo.png')
            ->title('Example attachment', 'http://docs.mattermost.com/developer/message-attachments.html')
        ;

        $this->assertSame('Example attachment', $attachment->toArray()['title']);
        $this->assertSame('https://example.com/photo.png', $attachment->toArray()['thumb_url']);

        $attachment->title('Overwritten info');
        $this->assertSame('Overwritten info', $attachment->toArray()['title']);
        $this->assertSame('https://example.com/photo.png', $attachment->toArray()['thumb_url']);
    }
}
