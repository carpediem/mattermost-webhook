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

use JsonSerializable;
use Traversable;
use TypeError;

final class Message implements JsonSerializable
{
    /**
     * The text of the message.
     *
     * @var string
     */
    private $text = '';

    /**
     * The printed username of the message.
     *
     * @var string
     */
    private $username = '';

    /**
     * The channel of the message.
     *
     * @var string
     */
    private $channel = '';

    /**
     * The icon of the message.
     *
     * @var UriInterface
     */
    private $icon_url = '';

    /**
     * The attachments of the message.
     *
     * @var Attachment[]
     */
    public $attachments = [];

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array_filter($this->toArray(), __NAMESPACE__.'\\filter_array_value');
    }

    /**
     * Returns the array representation of the object
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param string $text
     *
     * @return self
     */
    public function text(string $text): self
    {
        $this->text = trim($text);

        return $this;
    }

    /**
     * @param string $username
     *
     * @return self
     */
    public function username(string $username): self
    {
        $this->username = trim($username);

        return $this;
    }

    /**
     * @param string $channel
     *
     * @return self
     */
    public function channel(string $channel): self
    {
        $this->channel = trim($channel);

        return $this;
    }

    /**
     * @param string|UriInterface $icon_url
     *
     * @return self
     */
    public function iconUrl($icon_url): self
    {
        $this->icon_url = filter_uri($icon_url, 'icon_url');

        return $this;
    }

    /**
     * Add an attachment for the message.
     *
     * @param Attachment|callable $attachment
     *
     * @return self
     */
    public function attachment($attachment): self
    {
        if (is_callable($attachment)) {
            $item = new Attachment();
            $attachment($item);

            $this->attachments[] = $item;
            return $this;
        }

        if ($attachment instanceof Attachment) {
            $this->attachments[] = $attachment;

            return $this;
        }

        throw new TypeError(sprintf('The submitted argument must be a callable or a %s class', Attachment::class));
    }

    /**
     * Override all attachements with a iterable structure
     *
     * @param array|Traversable $attachments
     *
     * @return self
     */
    public function attachments($attachments): self
    {
        $this->attachments = [];
        foreach ($attachments as $attachment) {
            $this->attachment($attachment);
        }

        return $this;
    }
}
