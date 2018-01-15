# Changelog

All Notable changes to `mattermost-webhook` will be documented in this file

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