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

use Iterator;
use Traversable;
use TypeError;

final class Message implements MessageInterface
{
    /**
     * The text of the message.
     *
     * @var string
     */
    private $text;

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
    private $attachments = [];

    /**
     * Returns a new instance from an array
     *
     * @param array $arr
     *
     * @return self
     */
    public static function fromArray(array $arr): self
    {
        $text = $arr['text'] ?? '';
        $prop = $arr + (new self($text))->toArray();
        foreach ($prop['attachments'] as $offset => $attachment) {
            if (!$attachment instanceof Attachment) {
                $attachment = Attachment::fromArray($attachment);
            }
            $prop['attachments'][$offset] = $attachment;
        }

        return self::__set_state($prop);
    }

    /**
     * {@inheritdoc}
     */
    public static function __set_state(array $prop)
    {
        return (new self($prop['text']))
            ->setUsername($prop['username'])
            ->setChannel($prop['channel'])
            ->setIconUrl($prop['icon_url'])
            ->setAttachments($prop['attachments'])
        ;
    }

    /**
     * New instance
     *
     * @param string $text
     */
    public function __construct(string $text = '')
    {
        $this->setText($text);
    }

    /**
     * {@inheritdoc}
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function getIconUrl(): string
    {
        return $this->icon_url;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttachments(): Iterator
    {
        foreach ($this->attachments as $attachment) {
            yield $attachment;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        $prop = get_object_vars($this);
        foreach ($prop['attachments'] as $offset => $attachment) {
            $prop['attachments'][$offset] = $attachment->toArray();
        }

        return $prop;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $arr = get_object_vars($this);

        return array_filter($arr, __NAMESPACE__.'\\filter_array_value');
    }

    /**
     * Returns an instance with the specified message text.
     *
     * @param string $text
     *
     * @return self
     */
    public function setText(string $text): self
    {
        $text = trim($text);
        if ('' === $text) {
            throw new Exception('The text can not be empty');
        }

        $this->text = $text;

        return $this;
    }

    /**
     * Returns an instance with the specified message username.
     *
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
     * Returns an instance with the specified message channel.
     *
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
     * Returns an instance with the specified message icon URL.
     *
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
     * Returns an instance with the added attachment object.
     *
     * @param AttachmentInterface $attachment
     *
     * @return self
     */
    public function addAttachment(AttachmentInterface $attachment): self
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Override all attachements object with a iterable structure
     *
     * @param array|Traversable $attachments
     *
     * @return self
     */
    public function setAttachments($attachments): self
    {
        if (!is_iterable($attachments)) {
            throw new TypeError(sprintf('%s() expects argument passed to be iterable, %s given', __METHOD__, gettype($attachments)));
        }

        $this->attachments = [];
        foreach ($attachments as $attachment) {
            $this->addAttachment($attachment);
        }

        return $this;
    }
}
