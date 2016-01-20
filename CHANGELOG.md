## v0.7.0 (2016-01-??)

### Features
- Better and more complete `User` model with corresponding UI (#17).

### Fixes
- Fixed wrong pagination limit.

## v0.6.0 (2016-01-20)

### Libraries
- Added **angular-drag-and-drop-lists** version 1.3.0.

### Breaking changes
- Javascript **heavy** refactoring.

### Features
- Better and more complete `Gallery` model with corresponding UI (#16).
- Installation: added website title field (#15).

### Fixes
- Installation: fixed `chmod` and `mkdir` warnings (#14).

## v0.5.0 (2016-01-18)

### Libraries
- Added **Embed** version 2.6.1.

### Breaking changes
- `Content::setTags` is now a public method.

### Features
- Better and more complete `Media` model with corresponding UI.
- Import media from external providers.
- Open contents list inside modal for selection.

### Fixes
- Fixed wrong `Page` edit link on content detail.
- Fixed default `priority` on `Page` creation.
- Fixed missing `starred` field on `media` table during development phase.

## v0.4.0 (2016-01-13)

### Libraries
- Updated **Slim framework** to version 3.1.0.
- Added **Parsedown** version 1.6.
- Added **Stringy** version 2.2.

### Breaking changes
- JSON-serialized model have now *camelized* properties.

### Features
- Better and more complete `Page` model with corresponding UI.
- Added `TextContent` model to extend for text-based models (_e.g._ `Page`, `Post`).

### Fixes
- Check unique slug for content on saving.
- Added same regular expression check for slug for PHP and Javascript (266cfeb).
- Added remote content status calculation.

## v0.3.0 (2015-12-29)

### Libraries
- Added **PHPMailer** version 5.2.

### Breaking changes
- `User::authenticate` now accepts only instance of `User` to save in session.

### Features
- Added `CHANGELOG.md` file.
- Added password recovery flow (#6).
- Added `User::login` to replace old `User::authenticate` method.
- Added basic administration dashboard.
- **UI**: better components spacing and dimensions (#7).

## v0.2.0 (2015-12-16)

### Libraries
- Updated **Slim framework** to version 3.0.0 (4df8c81).

### Features
- Better step-by-step requirements check and admin user creation (#2).
- Autologin to dashboard after installation (#4).
- Added same regular expression check for username and email for PHP and Javascript (559a413).

## v0.1.0 (2015-12-04)

First release.
