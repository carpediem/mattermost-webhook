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

use GuzzleHttp\Psr7;
use Psr\Http\Message\UriInterface;

/**
 * Filter Uri
 *
 * @param string|UriInterface $raw_url
 *
 * @throws Exception If the value is not a valid URL
 *
 * @return UriInterface
 */
function filter_uri($raw_url): string
{
    $url = Psr7\uri_for($raw_url);
    if (!in_array($url->getScheme(), ['http', 'https'], true)) {
        throw new Exception(sprintf('the URL must contains a HTTP or HTTPS scheme %s', $raw_url));
    }

    return (string) $url;
}

/**
 * Filter out array value
 *
 * This function is to be used in a array_filter call
 * Only non empty string and array are kept every thing
 * else is removed from the array
 *
 * @param mixed $prop
 *
 * @return bool
 */
function filter_array_value($prop): bool
{
    if (is_string($prop) && '' !== $prop) {
        return true;
    }

    if ($prop instanceof UriInterface) {
        return true;
    }

    return is_array($prop) && !empty($prop);
}
