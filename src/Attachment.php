<?php
/**
 * This file is part of the carpediem mattermost webhook library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/carpediem/mattermost-php/
 * @version 1.1.0
 * @package carpediem.mattermost-webhook
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carpediem\Mattermost\Webhook;

use Iterator;
use JsonSerializable;
use Traversable;

final class Attachment implements JsonSerializable
{
    /**
     * A required plain-text summary of the post.
     *
     * This is used in notifications, and in clients that don’t support formatted text (eg IRC).
     *
     * @var string
     */
    private $fallback = '';

    /**
     * A hex color code that will be used as the left border color for the attachment.
     *
     * If not specified, it will default to match the left hand sidebar header background color.
     *
     * @var string
     */
    private $color = '';

    /**
     * An optional line of text that will be shown above the attachment.
     *
     * @var string
     */
    private $pretext = '';

    /**
     * The text to be included in the attachment.
     *
     * It can be formatted using markdown.
     * If it includes more than 700 characters or more than 5 line breaks,
     * the message will be collapsed and a “Show More” link will be added
     * to expand the message.
     *
     * @var string
     */
    private $text = '';

    /**
     * An optional name used to identify the author.
     *
     * It will be included in a small section at the top of the attachment.
     *
     * @var string
     */
    private $author_name = '';

    /**
     * An optional URL used to hyperlink the author_name.
     *
     * If no author_name is specified, this field does nothing.
     *
     * @var string
     */
    private $author_link = '';

    /**
     * An optional URL used to display a 16x16 pixel icon beside the author_name.
     *
     * @var string
     */
    private $author_icon = '';

    /**
     * An optional title displayed below the author information in the attachment.
     *
     * @var string
     */
    private $title = '';

    /**
     * An optional URL used to hyperlink the title.
     *
     * If no title is specified, this field does nothing.
     *
     * @var string|null
     */
    private $title_link;

    /**
     * Fields can be included as an optional array within attachments,
     * and are used to display information in a table format inside the attachment.
     *
     * @var array
     */
    private $fields = [];

    /**
     * An optional URL to an image file (GIF, JPEG, PNG, or BMP)
     * that will be displayed inside a message attachment.
     *
     * Large images will be resized to a maximum width of 400px
     * or a maximum height of 300px, while still maintaining the original aspect ratio.
     *
     * @var string|UriInterface
     */
    private $image_url = '';

    /**
     * An optional URL to an image file (GIF, JPEG, PNG, or BMP)
     * that will be displayed as a 75x75 pixel thumbnail on the right side of an attachment.
     *
     * We recommend using an image that is already 75x75 pixels,
     * but larger images will be scaled down with the aspect ratio maintained.
     *
     * @var string|UriInterface
     */
    private $thumb_url = '';

    /**
     * Returns a new instance from an array.
     *
     * @param array $arr
     *
     * @return self
     */
    public static function fromArray(array $arr)
    {
        $prop = $arr + (new self())->toArray();

        return self::__set_state($prop);
    }

    /**
     * {@inheritdoc}
     */
    public static function __set_state(array $prop)
    {
        return (new self())
            ->setFallback($prop['fallback'])
            ->setColor($prop['color'])
            ->setPretext($prop['pretext'])
            ->setText($prop['text'])
            ->setTitle($prop['title'], $prop['title_link'])
            ->setAuthor($prop['author_name'], $prop['author_link'], $prop['author_icon'])
            ->setFields($prop['fields'])
            ->setImageUrl($prop['image_url'])
            ->setThumbUrl($prop['thumb_url'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array_filter($this->toArray(), __NAMESPACE__.'\\filter_array_value');
    }

    /**
     * Returns the array representation of the Attachment
     *
     * @return array
     */
    public function toArray()
    {
        return  get_object_vars($this);
    }

    /**
      * @param string $fallback
      *
      * @return self
      */
    public function setFallback($fallback)
    {
        $this->fallback = filter_string($fallback, 'fallback');

        return $this;
    }

    /**
     * @return string
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * @param string $color
     *
     * @return self
     */
    public function setColor($color)
    {
        $this->color = filter_string($color, 'color');

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $pretext
     *
     * @return self
     */
    public function setPretext($pretext)
    {
        $this->pretext = filter_string($pretext, 'pretext');

        return $this;
    }

    /**
     * @return string
     */
    public function getPretext()
    {
        return $this->pretext;
    }

    /**
     * @param string $text
     *
     * @return self
     */
    public function setText($text)
    {
        $this->text = filter_string($text, 'text');

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set attachment author.
     *
     * @param string              $author_name
     * @param string|UriInterface $author_link
     * @param string|UriInterface $author_icon
     */
    public function setAuthor($author_name, $author_link = '', $author_icon = '')
    {
        $this->author_name = filter_string($author_name, 'author_name');
        if ('' === $this->author_name) {
            $this->author_link = '';
            $this->author_icon = '';

            return $this;
        }

        $this->author_link = filter_uri($author_link, 'author_link');
        $this->author_icon = filter_uri($author_icon, 'author_icon');

        return $this;
    }

    /**
     * Returns the author name.
     *
     * @return string
     */
    public function getAuthorName()
    {
        return $this->author_name;
    }

    /**
     * Returns the author link URI.
     *
     * @return string
     */
    public function getAuthorLink()
    {
        return $this->author_link;
    }

    /**
     * Returns the author icon URI.
     *
     * @return string
     */
    public function getAuthorIcon()
    {
        return $this->author_icon;
    }

    /**
     * Sets the author name.
     *
     * @param string $author_name
     *
     * DEPRECATION WARNING! This method will be removed in the next major point release
     *
     * @deprecated deprecated since version 1.1.0
     * @see Attachment::setAuthor
     *
     * @return self
     */
    public function setAuthorName($author_name)
    {
        return $this->setAuthor($author_name, $this->author_link, $this->author_icon);
    }

    /**
     * Sets the author link URI.
     *
     * @param string|UriInterface $author_link
     *
     * DEPRECATION WARNING! This method will be removed in the next major point release
     *
     * @deprecated deprecated since version 1.1.0
     * @see Attachment::setAuthor
     *
     * @return self
     */
    public function setAuthorLink($author_link)
    {
        return $this->setAuthor($this->author_name, $author_link, $this->author_icon);
    }

    /**
     * Sets the author name icon URI.
     *
     * @param string|UriInterface $author_icon
     *
     * DEPRECATION WARNING! This method will be removed in the next major point release
     *
     * @deprecated deprecated since version 1.1.0
     * @see Attachment::setAuthor
     *
     * @return self
     */
    public function setAuthorIcon($author_icon)
    {
        return $this->setAuthor($this->author_name, $this->author_link, $author_icon);
    }

    /**
     * @param string              $title
     * @param string|UriInterface $title_link
     *
     * @return self
     */
    public function setTitle($title, $title_link = null)
    {
        $this->title = filter_string($title, 'title');
        if ('' === $this->title || null === $title_link) {
            $this->title_link = null;

            return $this;
        }

        $this->title_link = filter_uri($title_link, 'title_link');

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getTitleLink()
    {
        return $this->title_link;
    }

    /**
     * Override all fields with an array
     *
     * @param array|Traversable $fields
     *
     * @return self
     */
    public function setFields($fields)
    {
        if (!is_iterable($fields)) {
            throw new Exception(sprintf('%s() expects argument passed to be iterable, %s given', __METHOD__, gettype($fields)));
        }

        $this->fields = [];
        foreach ($fields as $field) {
            $this->addField(...$field);
        }

        return $this;
    }

    /**
     * Add a field to the attachment
     *
     * @param string $title A title shown in the table above the value.
     * @param string $value The text value of the field. It can be formatted using markdown.
     * @param bool   $short to indicate whether the value is short enough
     *                      to be displayed beside other values - Optional
     *
     * @return self
     */
    public function addField($title, $value, $short = true)
    {
        $this->fields[] = [
            'title' => filter_string($title, 'field title'),
            'value' => filter_string($value, 'field value'),
            'short' => (bool) $short,
        ];

        return $this;
    }

    /**
     * @return Iterator
     */
    public function getFields()
    {
        foreach ($this->fields as $field) {
            yield $field;
        }
    }

    /**
     * @param string|UriInterface $image_url
     *
     * @return self
     */
    public function setImageUrl($image_url)
    {
        $this->image_url = filter_uri($image_url, 'image_url');

        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    /**
     * @param string|UriInterface $thumb_url
     *
     * @return self
     */
    public function setThumbUrl($thumb_url)
    {
        $this->thumb_url = filter_uri($thumb_url, 'thumb_url');

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbUrl()
    {
        return $this->thumb_url;
    }
}
