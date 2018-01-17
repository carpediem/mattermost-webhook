<?php
/**
 * This file is part of the carpediem mattermost webhook library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/carpediem/mattermost-webhook/
 * @version 2.2.0
 * @package carpediem.mattermost-webhook
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Carpediem\Mattermost\Webhook;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Throwable;

final class Client implements ClientInterface
{
    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * Guzzle options
     *
     * @var array
     */
    private $options = [];

    /**
     * New instance
     *
     * @param GuzzleClient $client
     * @param array        $options additional Guzzle options
     */
    public function __construct(GuzzleClient $client, array $options = [])
    {
        $this->client = $client;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function notify($url, MessageInterface $message): ResponseInterface
    {
        if (!$message instanceof Message) {
            $message = Message::fromArray($message->toArray());
        }

        return $this->send($url, $message, $this->options);
    }

    /**
     * Send a message to a Mattermost Webhook
     *
     * DEPRECATION WARNING! This method will be removed in the next major point release
     *
     * @deprecated deprecated since version 1.1.0
     * @see Client::notify
     *
     * @param string|UriInterface $url
     * @param MessageInterface    $message
     * @param array               $options additionals Guzzle options
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function send($url, Message $message, array $options = []): ResponseInterface
    {
        try {
            unset($options['body']);
            $options['json'] = $message;
            $options['Content-Type'] = 'application/json';

            return $this->client->request('POST', $url, $options);
        } catch (Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}
