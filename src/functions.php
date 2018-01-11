<?php

namespace Carpediem\Mattermost\Webhook;

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
 * @param mixed  $var
 * @param string $name
 * @param mixed  $raw_url
 *
 * @throws Exception If the value is not a valid URL
 *
 * @return string
 */
function filter_uri($raw_url, $name = '')
{
    $url = filter_var(filter_string($raw_url, 'name'), FILTER_VALIDATE_URL);
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
function filter_array_value($prop)
{
    return (is_string($prop) && '' !== $prop)
        || (is_array($prop) && !empty($prop));
}
