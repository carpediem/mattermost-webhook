<?php

namespace Carpediem\Mattermost;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

final class Client
{
    /**
     * @var Client
     */
    private $client;

    /**
     * New instance
     *
     * @param GuzzleClient $client
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send a message to a Mattermost Webhook
     *
     * @param Message             $message
     * @param string|UriInterface $url
     * @param array               $options additionals Guzzle options
     *
     * @return ResponseInterface
     */
    public function send($url, Message $message, array $options = [])
    {
        $options['Content-Type'] = 'application/json';
        $options['json'] = $message;

        return $this->client->request('POST', $url, $options);
    }
}
