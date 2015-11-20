<?php

require NOPE_LIB_DIR . 'vendor/autoload.php';
require NOPE_LIB_DIR . 'nope/Nope.php';
require NOPE_LIB_DIR . 'functions.php';
require NOPE_DIR . 'config.php';

session_start();

use RedBeanPHP\R as R;

date_default_timezone_set(NOPE_DATETIME_TIMEZONE);

define('NOPE_THEME_DEFAULT_PATH', NOPE_DIR . 'theme/'. NOPE_THEME .'/');
define('NOPE_APP_VIEWS_PATH', NOPE_DIR . 'app/views/');
define('NOPE_LIB_VIEWS_PATH', NOPE_LIB_DIR . 'views/');

$configuration = [
  'settings' => [
    'displayErrorDetails' => true,
  ],
];

// Create container
$container = new \Slim\Container($configuration);

// Register component on container
$container['view'] = function ($c) {
  $view = new \Nope\View([
    NOPE_LIB_VIEWS_PATH,
    NOPE_APP_VIEWS_PATH,
    NOPE_THEME_DEFAULT_PATH
  ]);
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



$app = new \Slim\App($container);

// register models
\Nope::registerModel('user', [
  'model' => NOPE_LIB_DIR . 'models/User.php',
  'route' => [
    NOPE_LIB_DIR . 'routes/index.php',
    NOPE_LIB_DIR . 'routes/auth.php',
    NOPE_LIB_DIR . 'routes/user.php'
  ],
  'js' => [
    'lib/assets/js/app.js',
    'lib/assets/js/ui.js'
  ]
]);
\Nope::registerModel('setting', [
  'model' => NOPE_LIB_DIR . 'models/Setting.php',
  'route' => NOPE_LIB_DIR . 'routes/setting.php'
]);

// register roles
\Nope::registerRole('admin', [
  'label' => 'Admin',
  'permissions' => ['*.*']
]);

require NOPE_LIB_DIR . 'models/Content.php';
require NOPE_DIR . 'app/register.php';


$models = \Nope::getConfig('nope.models');
foreach($models as $name => $definition) {
  if($definition['model']) {
    if(is_array($definition['model'])) {
      foreach($definition['model'] as $file) {
        if(file_exists($file)) {
          require $file;
        }
      }
    } else {
      require $definition['model'];
    }
  }
  if($definition['route']) {
    if(is_array($definition['route'])) {
      foreach($definition['route'] as $file) {
        if(file_exists($file)) {
          require $file;
        }
      }
    } else {
      require $definition['route'];
    }
  }
}

foreach(\Nope::getConfig('nope.routes') as $file) {
  if(file_exists($file)) {
    require $file;
  }
}

$app->add(new Nope\Middleware\Install());

$app->run();
