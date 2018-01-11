<?php

require dirname(__DIR__).'/vendor/autoload.php';

use Carpediem\Mattermost\Webhook\Attachment;
use Carpediem\Mattermost\Webhook\Client;
use Carpediem\Mattermost\Webhook\Message;
use GuzzleHttp\Client as GuzzleClient;

$mattermost = new Client(new GuzzleClient());

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
            ->title('Example attachment', 'http://docs.mattermost.com/developer/message-attachments.html')
            ->field('Long field', 'Testing with a very long piece of text that will take up the whole width of the table. And then some more text to make it extra long.', false)
            ->field('Column one', 'Testing.', true)
            ->field('Column two', 'Testing.', true)
            ->field('Column one again', 'Testing.', true)
            ->imageUrl('http://www.mattermost.org/wp-content/uploads/2016/03/logoHorizontal_WS.png')
        ;
    })
;

$mattermost->send('https://talk.2town.net/hooks/en8t69azpjbfiq8ysjdf893b7r', $message);
