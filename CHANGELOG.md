# Changelog

All Notable changes to `mattermost-webhook` will be documented in this file

## Next - TBD

### Added

- `MessageInterface`
- `AttachmentInterface`
- `ClientInterface`
- `Client::notify`

### Fixed

- `Client::send` always throws an `Carpediem\Mattermost\Exception`
- `Attachment::getTitleLink` always returns a string
- `Attachment::getFields` is made more relaxed to allow iterable return type
- `Message::getAttachments` is made more relaxed to allow iterable return type
- A `Attachment` must have at least a non empty `fallback` property
- A `Message` must have at least a non empty `text` property
- `Attachment::setFallback` must throws if the fallback value is the empty string
- `Message::setText` must throws if the text value is the empty string

### Deprecated

- None

### Removed

- None

## 2.1.1 - 2018-01-16

### Added

- None

### Fixed

- `filter_uri` missing second argument added
- `Client::send` throws an `Carpediem\Mattermost\Exception`

### Deprecated

- None

### Removed

- None

## 2.1.0 - 2018-01-15

### Added

- `Message::fromArray`
- `Message::__set_state`
- `Attachment::fromArray`
- `Attachment::__set_state`
- `Attachment::setAuthor`

### Fixed

- `Message::attachment` property visibility
- `filter_uri` should not throw on empty string

### Deprecated

- `Attachment::setAuthorName` use `Attachment::setAuthor` instead
- `Attachment::setAuthorLink` use `Attachment::setAuthor` instead
- `Attachment::setAuthorIcon` use `Attachment::setAuthor` instead

### Removed

- None

## 2.0.0 - 2018-01-12

- Initial release (drop support for PHP5)