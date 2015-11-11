<?php

require 'nope/vendor/autoload.php';
require 'nope/lib/Nope.php';
require 'nope/config.php';

$app = new \Slim\App($container);

# admin routes
require 'nope/admin/routes/index.php';
require 'nope/admin/routes/auth.php';
# admin models
require 'nope/admin/models/User.php';
require 'nope/admin/models/Setting.php';
# app routes
require 'nope/app/routes/index.php';

$app->add(new Nope\Middleware\Install());

$app->run();
