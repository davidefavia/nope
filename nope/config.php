<?php

session_start();

use RedBeanPHP\R as R;

date_default_timezone_set('Europe/Rome');

require 'lib/functions.php';

$basePath = basename(dirname(__DIR__.'../'));
$thisFolder = basename(__DIR__);
$dbName = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . '/nope.db';

define('NOPE_SALT', '$2y$12$NYDSfg.DBr0ZmdfR7AyS7OU16p8VRREi2ozHGsmIh4efQz9LIbw7S');
define('NOPE_ADMIN_ROUTE', '/admin');
define('NOPE_DATABASE_PATH', $dbName);
define('NOPE_PATH', $basePath . '/' . $thisFolder . '/');
define('NOPE_STORAGE_PATH', __DIR__ . '/storage/');
define('NOPE_THEME_DEFAULT_PATH', __DIR__ . '/theme/default/');
define('NOPE_ADMIN_VIEWS_PATH', __DIR__ . '/admin/views/');

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
try {
  $db = new SQLite3(NOPE_DATABASE_PATH, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
  R::setup('sqlite:'.NOPE_DATABASE_PATH);
  R::freeze(false);
  R::debug(false);
} catch(\Exception $e) {

}
