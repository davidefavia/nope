<div style="text-align:center">
  <img src="nope/lib/assets/img/nope.png" alt="nope!" title="nope!" />
</div>

### :shit: Crapware warning! :shit:
Almost badly written, badly commented and documented, not tested. I'm working on it in the few spare time!

# nope!
**nope!** (_a.k.a._ **n!**) is a content management system written in PHP, suitable for small projects.

## Requirements

- PHP >= 5.5.x with:
  - *PDO* with *SQLite* extension,
  - *GD* image processing library
- Apache2 >= 2.4.x with *mod_rewrite*
- [composer][composer]

## Install

Download or clone this repository:

```
$ git clone https://github.com/davidefavia/nope.git
```

Use [composer][composer] to manage PHP dependencies.

```
$ composer install
```

Go to [http://localhost/admin](http://localhost/admin) if you prefer to use installation directory as `DocumentRoot`.
Go to [http://localhost/path/to/installation/admin](http://localhost/path/to/installation/admin) if you installed **nope!** inside a subfolder or `DocumentRoot`.

Simple installation process will drive you through requirements and folder permissions.

## Libraries
**nope!** does not pretend to reinvent the wheel, so it takes advantage of the following libraries:

- [Slim framework][slim]
- [RedbeanPHP][redbeanphp]
- [Respect\Validation][validation]
- [Intervention\Image][image]
- [PHPMailer][phpmailer]
- [Stringy][stringy]
- [Parsedown][parsedown]
- League\Event

Administration interface is built on top of:

- [AngularJS][angular]
- [Bootstrap 3][bootstrap]
- [Font Awesome][fontawesome]

## Why (if anyone is interested)?

**TL;DR** I :heart: [WordPress][wordpress] but I need something simpler.

I worked a lot with [WordPress][wordpress] for customers and personal projects. Every single project had the same path for me.

1. Download WordPress locally.
2. Create MySQL database.
3. Install WordPress.
4. Install set of useful plugins.
5. Define custom post types.
6. Develop custom plugins and theme.
7. Insert content.
8. Set permissions and menu to forbid administration settings change.
9. Export MySQL database.
10. FTP to production.
11. Import MySQL database to production environment (via PHPMyAdmin or MySQL GUI client if database server is remotely accessible).
12. MySQL search and replace for URLs.
13. Write instructions for customer about how to insert new content, manage images, carousels, widgets and anything else I developed to customize every single _fu**ing_ box/carousel/list in the sidebar/footer (that probably will never change in future).
14. Backup database and FTP all changes (_e.g._ uploaded files) to local environment.

_Maybe a lot of this stuff is achievable via plugins I never used or discovered or learnt how to configure. Things could be easier even with this workflow, but not for me at this time._

I'm tired of this workflow, I'm tired of an heavy administration interface and different plugins UI. I spent more time to hide features or configure administration than to develop good code, I neither updated my code customizations nor the platform. I NEVER had the need to add plugins or change theme _on-the-fly_ through administration interface without testing them locally in advance.

I need something radically different:

1. Download and install **nope!** locally.
2. Enable or define (and develop code for) custom content types via PHP files.
3. Develop custom theme and plugins/hooks.
4. Set roles and permissions inside a PHP file.
5. Insert content.
6. Build package for production.
7. FTP to production.
8. Easy backup and restore (_e.g._ compressed file with SQLite database and uploaded files).

If I need something new (1% of cases), I develop locally and I update everything (except database, maybe).

**Don't get me wrong**: I :heart: [WordPress][wordpress], but it doesn't fit my needs anymore. I need something simpler even if still customizable and hookable. Few actions: write content, upload files, create gallery, build a custom theme, let people add/edit and organize content without full administration privileges. If I need more features, I'll develop them, simple.

## License
See [LICENSE](LICENSE) file.

[angular]: https://angularjs.org/
[bootstrap]: https://getcomposer.org/
[composer]: http://getcomposer.org
[fontawesome]: https://fortawesome.github.io/Font-Awesome/
[image]: http://image.intervention.io/
[parsedown]: https://github.com/erusev/parsedown
[phpmailer]: https://github.com/PHPMailer/PHPMailer
[redbeanphp]: http://www.redbeanphp.com/
[slim]: http://www.slimframework.com/
[stringy]: https://github.com/danielstjules/Stringy
[validation]: https://github.com/Respect/Validation
[wordpress]: http://wordpress.org
