<?php

namespace Carpediem\Mattermost\Webhook\Test;

use Carpediem\Mattermost\Webhook\Client;
use Carpediem\Mattermost\Webhook\Exception;
use Carpediem\Mattermost\Webhook\Message;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Carpediem\Mattermost\Webhook\Mattermost
 */
final class ClientTest extends TestCase
{
    public function testSendingInfo()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([new Response(200, ['X-Foo' => 'Bar'])]);
        $handler = HandlerStack::create($mock);
        $httpClient = new GuzzleClient(['handler' => $handler]);
        $client = new Client($httpClient);
        $res = $client->send('http://example.com', new Message(), ['http_errors' => false]);
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
        $res = $client->send('http://example.com', new Message());
    }
}
