## v0.12.0 (2016-02-??)

### Libraries
- Added **SimpleMDE** version 1.10.0.

### Features
- Added loading spinner (#36).
- Added datetime picker (#36).
- Added markdown editor (#36).

### Fixes
- Fixed block button on empty states.

## v0.11.0 (2016-02-16)

### Features
- `Menu` model and UI (#23).
- Added `nopeMenu` directive to enable menu recursive UI.
- Added homepage link to administration panel (#35).

## v0.10.0 (2016-02-05)

### Breaking changes
- `nopeModel` is now `multiple=false` by default.

### Features
- Added input fields placeholders (#27).
- Added `nopeContentSelection` directive to enable selection inside iframe (#29).
- Added `template` property to `nopeModel` directive (#30).
- Added check if `localhost` to enable livereload server binding inside views (#26).

### Fixes
- Fixed wrong `headline` setting creation during installation.
- Fixed first gallery creation UI (#25).
- Fixed user detail UI.
- Fixed missing default fields (`type` and `provider`) on media upload.
- Fixed reset password error without exception (#28).
- Fixed multiple models saving (#32).
- Fixed excluded ids content query (#31).
- Fixed missing content highlight inside lists (#24).
- CSS refactoring, `Model::__getSql()` method optimization (#33).

## v0.9.0 (2016-02-03)

### Libraries
- Updated **angular** to version 1.4.9.
- Updated `composer` libraries.
- Added **Carbon** version 1.21.

### Features
- Added `Nope\String`, `Nope\Text`, `Nope\DateTime` classes.
- Added `Nope\Query` class to obtain items different from JSON-encoded data.
- Default theme based on [milligram](https://github.com/milligram/milligram) (#21).
- Added `less` watch and compile for default theme.

### Fixes
- Fixed iframe for `Content`, `Gallery` and `User` (#22).

## v0.8.0 (2016-01-27)

### Features
- `Setting` model and UI (#19).
- Platform settings type: `input`, `text`, `pair`, `table`, `model`, `group`, `select`, `checkbox`.
- Custom settings for each model (#19).
- `Media` thumbnail basic editing: rotation clockwise and counterclockwise.

### Fixes
- Fixed wrong `Media::isImage` method check.
- Fixed empty `title` or `filename` too long exceptions on `Media` import (#18).

## v0.7.0 (2016-01-20)

### Features
- Better and more complete `User` model with corresponding UI (#17).

### Fixes
- Fixed wrong pagination limit.
- Fixed misleading empty results message (#10).

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
