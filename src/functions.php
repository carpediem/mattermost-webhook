<?php
/**
 * This file is part of the Carpediem.Errors library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/carpediem/mattermost-php/
 * @version 1.0.0
 * @package carpediem.mattermost-php
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carpediem\Mattermost\Webhook;

use GuzzleHttp\Psr7;
use Psr\Http\Message\UriInterface;

/**
 * Filter string
 *
 * @param mixed  $var
 * @param string $name
 *
 * @throws Exception If the value can not be stringify
 *
 * @return string
 */
function filter_string($var, $name = '')
{
    if (is_string($var) || (is_object($var) && method_exists($var, '__toString'))) {
        return trim((string) $var);
    }

    throw new Exception(sprintf('Expected %s to a a string %s received', $name, gettype($var)));
}

/**
 * Filter Uri
 *
 * @param string|UriInterface $raw_url
 *
 * @throws Exception If the value is not a valid URL
 *
 * @return string
 */
function filter_uri($raw_url)
{
    $url = Psr7\uri_for($raw_url);
    if (in_array($url->getScheme(), ['http', 'https'], true)) {
        return (string) $url;
    }

    throw new Exception(sprintf('the URL must contains a HTTP or HTTPS scheme %s', $raw_url));
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
function filter_array_value($prop)
{
    return (is_string($prop) && '' !== $prop)
        || (is_array($prop) && !empty($prop));
}
