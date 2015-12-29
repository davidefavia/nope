## v0.3.0 (2015-12-??)

### Libraries
- Added **PHPMailer** version 5.2.

### Breaking changes
- `User::authenticate` now accepts only instance of `User` to save in session.

### Features
- Added `CHANGELOG.md` file.
- Added password recovery flow (#6).
- Added `User::login` to replace old `User::authenticate` method.
- Added basic administration dashboard.
- **UI**: better components spacing and dimensions.

## v0.2.0 (2015-12-16)

### Libraries
- Updated **Slim framework** to version 3.0.0 (4df8c81).

### Features
- Better step-by-step requirements check and admin user creation (#2).
- Autologin to dashboard after installation (#4).
- Added same regular expression check for username and email for PHP and Javascript (559a413).

## v0.1.0 (2015-12-04)

First release.
