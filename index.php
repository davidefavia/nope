<?php

require 'nope/vendor/autoload.php';
require 'nope/config.php';

$app = new \Slim\App($container);

require 'nope/admin/routes/index.php';
require 'nope/admin/routes/auth.php';
require 'nope/app/routes/index.php';

$app->run();
