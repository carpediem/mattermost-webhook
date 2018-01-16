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

namespace Carpediem\Mattermost\Webhook;

use JsonSerializable;

interface MessageInterface extends JsonSerializable
{
    /**
     * Returns the text.
     *
     * @return string
     */
    public function getText(): string;

    /**
     * Returns the username.
     *
     * @return string
     */
    public function getUsername(): string;

    /**
     * Returns the channel.
     *
     * @return string
     */
    public function getChannel(): string;

    /**
     * Returns the icon URL.
     *
     * @return string
     */
    public function getIconUrl(): string;

    /**
     * Returns the collection of AttachementInterface objects
     *
     * @return AttachementInterface[]
     */
    public function getAttachments();

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
