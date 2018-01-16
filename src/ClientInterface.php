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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

interface ClientInterface
{
    /**
     * Send a message to a Mattermost Webhook
     *
     * @param string|UriInterface $url
     * @param MessageInterface    $message
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function notify($url, MessageInterface $message): ResponseInterface;
}
