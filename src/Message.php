<?php
/**
 * This file is part of the carpediem mattermost webhook library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/carpediem/mattermost-php/
 * @version 2.0.0
 * @package carpediem.mattermost-webhook
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Carpediem\Mattermost\Webhook;

use Iterator;
use JsonSerializable;
use Traversable;

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
        $arr = get_object_vars($this);

        return array_filter($arr, __NAMESPACE__.'\\filter_array_value');
    }

    /**
     * Returns the array representation of the object
     *
     * @return array
     */
    public function toArray(): array
    {
        $arr = get_object_vars($this);

        foreach ($arr['attachments'] as $offset => $attachment) {
            $arr['attachments'][$offset] = $attachment->toArray();
        }

        return $arr;
    }

    /**
     * @param string $text
     *
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = trim($text);

        return $this;
    }

    /**
     * Returns the text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $username
     *
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = trim($username);

        return $this;
    }

    /**
     * Returns the username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $channel
     *
     * @return self
     */
    public function setChannel(string $channel): self
    {
        $this->channel = trim($channel);

        return $this;
    }

    /**
     * Returns the channel
     *
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string|UriInterface $icon_url
     *
     * @return self
     */
    public function setIconUrl($icon_url): self
    {
        $this->icon_url = filter_uri($icon_url, 'icon_url');

        return $this;
    }

    /**
     * Returns the icon url
     *
     * @return string
     */
    public function getIconUrl(): string
    {
        return $this->icon_url;
    }

    /**
     * Add an attachment for the message.
     *
     * @param Attachment|callable $attachment
     *
     * @return self
     */
    public function addAttachment(Attachment $attachment): self
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Override all attachements with a iterable structure
     *
     * @param array|Traversable $attachments
     *
     * @return self
     */
    public function setAttachments($attachments): self
    {
        $this->attachments = [];
        foreach ($attachments as $attachment) {
            $this->addAttachment($attachment);
        }

        return $this;
    }

    /**
     * Returns the Attachement objects
     *
     * @return Attachement[]
     */
    public function getAttachments(): Iterator
    {
        foreach ($this->attachments as $attachment) {
            yield $attachment;
        }
    }
}
