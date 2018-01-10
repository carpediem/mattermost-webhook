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

namespace Carpediem\Mattermost;

/**
 * Filter Uri
 *
 * @param string $raw_url
 *
 * @throws Exception If the value is not a valid URL
 *
 * @return string
 */
function filter_uri(string $raw_url): string
{
    $url = filter_var($raw_url, FILTER_VALIDATE_URL);
    if (!$url) {
        throw new Exception(sprintf('Malformed URL %s', $raw_url));
    }

    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    if (!in_array($scheme, ['http', 'https'], true)) {
        throw new Exception(sprintf('the URL must contains a HTTP or HTTPS scheme %s', $raw_url));
    }

    return $url;
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
    return (is_string($prop) && '' !== $prop)
        || (is_array($prop) && !empty($prop));
}
