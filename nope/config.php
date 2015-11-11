<?php

use RedBeanPHP\R as R;

date_default_timezone_set('Europe/Rome');

require 'lib/functions.php';

define('NOPE_SALT', '');
define('NOPE_ADMIN_ROUTE', '/admin');
define('NOPE_PATH', basename(__DIR__) . '/nope/');
define('NOPE_DATA_PATH', dirname(__DIR__) . '/nope/data/');
define('NOPE_THEME_DEFAULT_PATH', 'nope/theme/default/');
define('NOPE_ADMIN_VIEWS_PATH', 'nope/admin/views/');

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

// Create container
$container = new \Slim\Container($configuration);

// Register component on container
$container['view'] = function ($c) {
    $view = new \Nope\View(NOPE_THEME_DEFAULT_PATH, NOPE_ADMIN_VIEWS_PATH);
    return $view;
};

// Database
R::setup('sqlite:' . dirname(__DIR__) . '/nope/data/nope.db');
