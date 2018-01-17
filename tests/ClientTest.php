<?php

namespace Carpediem\Mattermost\Webhook\Test;

use Carpediem\Mattermost\Webhook\Client;
use Carpediem\Mattermost\Webhook\Exception;
use Carpediem\Mattermost\Webhook\Message;
use Carpediem\Mattermost\Webhook\MessageInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Carpediem\Mattermost\WebhookMattermost
 */
final class ClientTest extends TestCase
{
    public function testSendingInfo()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([new Response(200, ['X-Foo' => 'Bar'])]);
        $handler = HandlerStack::create($mock);
        $httpClient = new GuzzleClient(['handler' => $handler]);
        $client = new Client($httpClient, ['http_errors' => false]);
        $res = $client->notify('http://example.com', new Message('this is an example *text*'));
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testSendingInfoThrows()
    {
        // Create a mock and queue two responses.
        $this->expectException(Exception::class);
        $mock = new MockHandler([new Response(403, ['X-Foo' => 'Bar'])]);
        $handler = HandlerStack::create($mock);
        $httpClient = new GuzzleClient(['handler' => $handler]);
        $client = new Client($httpClient);
        $res = $client->notify('http://example.com', new Message('text'));
    }

    public function testNotifyWithMessageInterface()
    {
        $message = $this->createMock(MessageInterface::class);

        $message
            ->method('toArray')
            ->willReturn(['text' => 'test interface'])
        ;

        $mock = new MockHandler([new Response(200, ['X-Foo' => 'Bar'])]);
        $handler = HandlerStack::create($mock);
        $httpClient = new GuzzleClient(['handler' => $handler]);
        $client = new Client($httpClient, ['http_errors' => false]);
        $res = $client->notify('http://example.com', $message);
        $this->assertInstanceOf(Response::class, $res);
    }

    public function testClientThrowsExceptionOnInvalidJson()
    {
        // Create a mock and queue two responses.
        $this->expectException(Exception::class);
        $mock = new MockHandler([new Response(200, ['X-Foo' => 'Bar'])]);
        $handler = HandlerStack::create($mock);
        $httpClient = new GuzzleClient(['handler' => $handler]);
        $client = new Client($httpClient);
        $res = $client->notify('http://example.com', new Message(mb_convert_encoding('hé ça va', 'UTF-16', 'UTF-8')));
    }
}
