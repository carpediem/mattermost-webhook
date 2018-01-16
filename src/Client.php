<?php
/**
 * This file is part of the carpediem mattermost webhook library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/carpediem/mattermost-php/
 * @version 2.1.1
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

final class Client
{
    /**
     * @var GuzzleClient
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
     * @param string|UriInterface $url
     * @param Message             $message
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
            $options['Content-Type'] = 'application/json';
            $options['json'] = $message;

            return $this->client->request('POST', $url, $options);
        } catch (Throwable $e) {
            throw new Exception('An error occurs while sending your message', 1, $e);
        }
    }
}
