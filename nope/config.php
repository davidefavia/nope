<?php

require 'lib/functions.php';

define('NOPE_ADMIN_ROUTE', '/admin');
define('NOPE_PATH', basename(__DIR__) . '/nope/');
define('NOPE_THEME_DEFAULT_PATH', 'nope/theme/default/');
define('NOPE_ADMIN_VIEWS_PATH', 'nope/admin/views/');

// Create container
$container = new \Slim\Container;

// Register component on container
$container['view'] = function ($c) {
    $view = new \Nope\View(NOPE_THEME_DEFAULT_PATH, NOPE_ADMIN_VIEWS_PATH);
    return $view;
};
