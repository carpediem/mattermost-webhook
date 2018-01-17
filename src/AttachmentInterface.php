<?php
/**
 * This file is part of the carpediem mattermost webhook library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/carpediem/mattermost-webhook/
 * @version 1.2.0
 * @package carpediem.mattermost-webhook
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carpediem\Mattermost\Webhook;

use JsonSerializable;
use Traversable;

interface AttachmentInterface extends JsonSerializable
{
    /**
     * Returns the fallback text.
     *
     * @return string
     */
    public function getFallback();

    /**
     * Returns the attachment color.
     *
     * @return string
     */
    public function getColor();

    /**
     * Returns the pretext text.
     *
     * @return string
     */
    public function getPretext();

    /**
     * Returns the text.
     *
     * @return string
     */
    public function getText();

    /**
     * Returns the author name.
     *
     * @return string
     */
    public function getAuthorName();

    /**
     * Returns the author link URI.
     *
     * @return string
     */
    public function getAuthorLink();

    /**
     * Returns the author icon URI
     *
     * @return string
     */
    public function getAuthorIcon();

    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Returns the title link.
     *
     * @return string
     */
    public function getTitleLink();

    /**
     * Returns the fields collection as an iterable structure
     *
     * @return array|Traversable
     */
    public function getFields();

    /**
     * Returns the image URL.
     *
     * @return string
     */
    public function getImageUrl();

    /**
     * Returns the thumb URL.
     *
     * @return string
     */
    public function getThumbUrl();

    /**
     * Returns the array representation of the object
     *
     * @return array
     */
    public function toArray();

    /**
     * Returns the filtered representation of the object without
     * empty or null value.
     *
     * @return array
     */
    public function jsonSerialize();
}
