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

interface MessageInterface extends JsonSerializable
{
    /**
     * Returns the text.
     *
     * @return string
     */
    public function getText();

    /**
     * Returns the username.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Returns the channel.
     *
     * @return string
     */
    public function getChannel();

    /**
     * Returns the icon URL.
     *
     * @return string
     */
    public function getIconUrl();

    /**
     * Returns an iterable collection of AttachementInterface objects
     *
     * @return AttachementInterface[]
     */
    public function getAttachments();

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
