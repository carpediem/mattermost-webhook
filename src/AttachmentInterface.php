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

use JsonSerializable;

interface AttachmentInterface extends JsonSerializable
{
    /**
     * Returns the fallback text.
     *
     * @return string
     */
    public function getFallback(): string;

    /**
     * Returns the attachment color.
     *
     * @return string
     */
    public function getColor(): string;

    /**
     * Returns the pretext text.
     *
     * @return string
     */
    public function getPretext(): string;

    /**
     * Returns the text.
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Returns the author name.
     *
     * @return string
     */
    public function getAuthorName(): string;

    /**
     * Returns the author link URI.
     *
     * @return string
     */
    public function getAuthorLink(): string;

    /**
     * Returns the author icon URI
     *
     * @return string
     */
    public function getAuthorIcon(): string;

    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Returns the title link.
     *
     * @return string
     */
    public function getTitleLink(): string;

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
    public function getImageUrl(): string;

    /**
     * Returns the thumb URL.
     *
     * @return string
     */
    public function getThumbUrl(): string;

    /**
     * Returns the array representation of the object
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Returns the filtered representation of the object without
     * empty or null value.
     *
     * @return array
     */
    public function jsonSerialize();
}
