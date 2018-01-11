<?php
/**
* This file is part of the Carpediem.Errors library
*
* @license http://opensource.org/licenses/MIT
* @link https://github.com/carpediem/mattermost-php/
* @version 0.1.0
* @package carpediem.mattermost-php
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
declare(strict_types=1);

namespace Carpediem\Mattermost\Webhook;

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
     * @param string|UriInterface $url
     * @param Message             $message
     * @param array               $options additionals Guzzle options
     *
     * @return ResponseInterface
     */
    public function send($url, Message $message, array $options = []): ResponseInterface
    {
        unset($options['body']);
        $options['Content-Type'] = 'application/json';
        $options['json'] = $message;

        return $this->client->request('POST', $url, $options);
    }
}
