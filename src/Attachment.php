<?php
/**
 * This file is part of the Carpediem.Errors library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/carpediem/mattermost-php/
 * @version 1.0.0
 * @package carpediem.mattermost-webhook
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carpediem\Mattermost\Webhook;

use JsonSerializable;

final class Attachment implements JsonSerializable
{
    /**
     * A required plain-text summary of the post.
     *
     * This is used in notifications, and in clients that don’t support formatted text (eg IRC).
     *
     * @var string
     */
    private $fallback;

    /**
     * A hex color code that will be used as the left border color for the attachment.
     *
     * If not specified, it will default to match the left hand sidebar header background color.
     *
     * @var string
     */
    private $color;

    /**
     * An optional line of text that will be shown above the attachment.
     *
     * @var string
     */
    private $pretext;

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
    private $text;

    /**
     * An optional name used to identify the author.
     *
     * It will be included in a small section at the top of the attachment.
     *
     * @var string
     */
    private $author_name;

    /**
     * An optional URL used to hyperlink the author_name.
     *
     * If no author_name is specified, this field does nothing.
     *
     * @var string
     */
    private $author_link;

    /**
     * An optional URL used to display a 16x16 pixel icon beside the author_name.
     *
     * @var string
     */
    private $author_icon;

    /**
     * An optional title displayed below the author information in the attachment.
     *
     * @var string
     */
    private $title;

    /**
     * An optional URL used to hyperlink the title.
     *
     * If no title is specified, this field does nothing.
     *
     * @var string
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
     * @var string
     */
    private $image_url;

    /**
     * An optional URL to an image file (GIF, JPEG, PNG, or BMP)
     * that will be displayed as a 75x75 pixel thumbnail on the right side of an attachment.
     *
     * We recommend using an image that is already 75x75 pixels,
     * but larger images will be scaled down with the aspect ratio maintained.
     *
     * @var string
     */
    private $thumb_url;

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
        return get_object_vars($this);
    }

    /**
     * @param string $fallback
     *
     * @return self
     */
    public function fallback($fallback)
    {
        $this->fallback = filter_string($fallback, 'fallback');

        return $this;
    }

    /**
     * @param string $color
     *
     * @return self
     */
    public function color($color)
    {
        $this->color = filter_string($color, 'color');

        return $this;
    }

    /**
     * Set a green color for the attachment.
     *
     * @return self
     */
    public function success()
    {
        return $this->color('#22BC66');
    }

    /**
     * Set a red color for the attachment.
     *
     * @return self
     */
    public function error()
    {
        return $this->color('#DC4D2F');
    }

    /**
     * Set a blue color for the attachment.
     *
     * @return self
     */
    public function info()
    {
        return $this->color('#3869D4');
    }

    /**
     * @param string $pretext
     *
     * @return self
     */
    public function pretext($pretext)
    {
        $this->pretext = filter_string($pretext, 'pretext');

        return $this;
    }

    /**
     * @param string $text
     *
     * @return self
     */
    public function text($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @param string $author_name
     *
     * @return self
     */
    public function authorName($author_name)
    {
        $this->author_name = filter_string($author_name, 'author_name');

        return $this;
    }

    /**
     * @param string $author_link
     *
     * @return self
     */
    public function authorLink($author_link)
    {
        $this->author_link = filter_uri($author_link, 'author_link');

        return $this;
    }

    /**
     * @param string $author_icon
     *
     * @return self
     */
    public function authorIcon($author_icon)
    {
        $this->author_icon = filter_string($author_icon, 'author_icon');

        return $this;
    }

    /**
     * @param string $title
     * @param string $title_link
     *
     * @return self
     */
    public function title($title, $title_link = null)
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
     * Override all fields with an array
     *
     * @param array $fields
     *
     * @return self
     */
    public function fields(array $fields = [])
    {
        $this->fields = [];
        foreach ($fields as $field) {
            $this->field(...$field);
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
    public function field($title, $value, $short = true)
    {
        $this->fields[] = [
            'title' => filter_string($title, 'field title'),
            'value' => filter_string($value, 'field value'),
            'short' => (bool) $short,
        ];

        return $this;
    }

    /**
     * @param string $image_url
     *
     * @return self
     */
    public function imageUrl($image_url)
    {
        $this->image_url = filter_uri($image_url, 'image_url');

        return $this;
    }

    /**
     * @param string $thumb_url
     *
     * @return self
     */
    public function thumbUrl($thumb_url)
    {
        $this->thumb_url = filter_uri($thumb_url, 'thumb_url');

        return $this;
    }
}
