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

use Psr\Http\Message\UriInterface;

/**
 * Filter Uri
 *
 * @param string|UriInterface $raw_url
 * @param string              $name
 *
 * @throws Exception If the value is not a valid URL
 *
 * @return string
 */
function filter_uri($raw_url, string $name): string
{
    if ($raw_url instanceof UriInterface) {
        $raw_url = (string) $raw_url;
    }

    if (!is_string($raw_url)) {
        throw new Exception('the submitted URL must be an instanceof '.UriInterface::class.' or a string');
    }

    if ('' === $raw_url) {
        return $raw_url;
    }

    $parts = @parse_url($raw_url);
    if (isset($parts['scheme'], $parts['host']) && in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
        return $raw_url;
    }

    throw new Exception(sprintf('the URL for `%s` must contains a HTTP or HTTPS scheme `%s` given', $name, $raw_url));
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

if (!function_exists('\is_iterable')) {
    /**
     * Tell whether the content of the variable is iterable
     *
     * @see http://php.net/manual/en/function.is-iterable.php
     *
     * @param mixed $iterable
     *
     * @return bool
     */
    function is_iterable($iterable): bool
    {
        return is_array($iterable) || $iterable instanceof Traversable;
    }
}
