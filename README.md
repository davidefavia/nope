![nope](nope/admin/assets/img/nope.png "nope!")


### :shit: Crapware warning! :shit:
Almost badly written, badly commented and documented, not tested. I'm working on it in the few spare time!

# nope!
**nope!** (_a.k.a._ **n!**) is a content management system written in PHP, suitable for small projects.

## Requirements

- PHP >= 5.5.x (with _PDO_ and _SQLite_)
- Apache2 >= 2.4.x (with *mod_rewrite*)

## Libraries
**n!** does not pretend to reinvent the wheel, so it takes advantage of the following libraries:

- [Slim framework][slim]
- [RedbeanPHP][redbeanphp]
- [Respect\Validation][validation]
- Intervention
- League\Event

Administration interface is built on top of:

- [AngularJS][angular]
- [Bootstrap 3][bootstrap] (no jQuery!)
- Font Awesome

## Why (if anyone interested)?

**TL;DR** I :heart: [WordPress][wordpress] but I need something simpler.

I worked a lot with [WordPress][wordpress] for customers and personal projects. Every single project had the same path for me.

1. Download WordPress locally.
2. Create MySQL database.
3. Install WordPress.
2. Install set of useful plugins.
3. Define custom post types.
4. Develop custom plugins and theme.
5. Insert content.
6. Set permissions and menu to forbid administration settings change.
7. Export MySQL database.
8. FTP to production.
9. Import MySQL database to production environment (via PHPMyAdmin or MySQL GUI client if database server is remotely accessible).
10. MySQL search and replace for URLs.
11. Write instructions for customer about how to insert new content, manage images, carousels, widgets and anything else I developed to customize every single _fu**ing_ box/carousel/list in the sidebar/footer (that probably will never change in future).
12. Backup database and FTP to local environment all changes (_e.g._ uploaded files).

(Maybe a lot of this stuff is achievable via plugins I never used or discovered or learnt how to configure. Things could be easier even with this workflow, but not for me at this time.)

I'm tired of this workflow, I'm tired of an heavy administration interface and different plugins UI. I spent more time to hide features or configure administration than to develop good code, I neither updated my code customizations nor the platform. I NEVER had the need to add plugins or change theme _on-the-fly_ through administration interface when in production without deploying code from local environment (even if a single _CSS_ line).

I need something radically different:

1. Download and install **nope!** locally.
2. Enable or define (and develop code for) custom content types.
3. Develop custom theme and plugins/hooks.
4. Set roles and permissions inside a PHP file.
5. Insert content.
6. Build package for production.
7. FTP to production.
8. Easy backup (_e.g._ compressed file with SQLite database and uploaded files).

If I'll need something new (1% of cases), I'll develop locally and I'll update everything (except database, maybe).

**Don't get me wrong**: I :heart: [WordPress][wordpress], but it doesn't fit my needs anymore. I need something simpler even if still customizable and hookable. Few actions: write content, upload files, create gallery, build a custom theme, let people add/edit and organize content without full administration privileges. If I need more features, I need to develop them, simple.

## License
See [LICENSE](LICENSE) file.

[angular]: https://angularjs.org/
[bootstrap]: http://getbootstrap.com/
[redbeanphp]: http://www.redbeanphp.com/
[slim]: http://www.slimframework.com/
[validation]: https://github.com/Respect/Validation
[wordpress]: http://wordpress.org
