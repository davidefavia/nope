<?php

define('NOPE_DIR', realpath(__DIR__ . '/../') . '/');
define('NOPE_BASE_PATH', '/' . basename(dirname($_SERVER['SCRIPT_NAME'])) . '/');
define('NOPE_PATH', '/' . basename(dirname($_SERVER['SCRIPT_NAME'])) . '/nope/');

define('NOPE_LIB_DIR', NOPE_DIR . 'lib/');
define('NOPE_LIB_PATH', NOPE_PATH . 'lib/');
define('NOPE_APP_DIR', NOPE_DIR . 'app/');
define('NOPE_LIB_PATH', NOPE_PATH . 'app/');
define('NOPE_STORAGE_DIR', NOPE_DIR . 'storage/');
define('NOPE_STORAGE_PATH', NOPE_PATH . 'storage/');
define('NOPE_UPLOADS_DIR', NOPE_STORAGE_DIR . 'uploads/');
define('NOPE_UPLOADS_PATH', NOPE_STORAGE_PATH . 'uploads/');
define('NOPE_CACHE_DIR', NOPE_STORAGE_DIR . 'cache/');
define('NOPE_CACHE_PATH', NOPE_STORAGE_PATH . 'cache/');
define('NOPE_BACKUPS_DIR', NOPE_STORAGE_DIR . 'backups/');
define('NOPE_BACKUPS_PATH', NOPE_STORAGE_PATH . 'backups/');


require NOPE_LIB_DIR . 'vendor/autoload.php';
require NOPE_LIB_DIR . 'nope/Nope.php';
require NOPE_LIB_DIR . 'nope/functions.php';
if(file_exists(NOPE_DIR . 'config-dev.php')) {
  require NOPE_DIR . 'config-dev.php';
} else {
  require NOPE_DIR . 'config.php';
}

session_start();

use RedBeanPHP\R as R;

date_default_timezone_set(NOPE_DATETIME_TIMEZONE);

define('NOPE_THEME_DIR', NOPE_DIR . 'theme/'. NOPE_THEME .'/');
define('NOPE_THEME_PATH', NOPE_PATH . 'theme/'. NOPE_THEME .'/');
define('NOPE_APP_VIEWS_PATH', NOPE_APP_DIR . 'views/');
define('NOPE_LIB_VIEWS_PATH', NOPE_LIB_DIR . 'views/');

$configuration = [
  'settings' => [
    'displayErrorDetails' => true,
  ],
];

// Database
try {
  $db = new SQLite3(NOPE_DATABASE_PATH, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
  R::setup('sqlite:'.NOPE_DATABASE_PATH);
  R::freeze(false);
  R::exec('PRAGMA foreign_keys = 1;');
  R::debug(false);
} catch(\Exception $e) {
}


$isNopeEmbedded = (defined('NOPE_EMBEDDED') && NOPE_EMBEDDED===true);

if(!$isNopeEmbedded) {
  // Create container
  $container = new \Slim\Container($configuration);

  // Register component on container
  $container['view'] = function ($c) {
    $view = new \Nope\View([
      NOPE_LIB_VIEWS_PATH,
      NOPE_APP_VIEWS_PATH
    ], [
      NOPE_THEME_DIR
    ]);
    return $view;
  };
  // Register mailer
  $container['mailer'] = function($c) {
    $mail = new PHPMailer;
    $settings = \Nope::getConfig('nope.mailer');

    $mail->From = $settings->sender->email;
    $mail->FromName = $settings->sender->name;

    if($settings->useSMTP) {
      $mail->SMTPDebug = 3; // Enable verbose debug output
      $mail->isSMTP(); // Set mailer to use SMTP
      $mail->Host = $settings->host; // Specify main and backup SMTP servers
      $mail->SMTPAuth = $settings->SMTPAuth; // Enable SMTP authentication
      $mail->Username = $settings->username; // SMTP username
      $mail->Password = $settings->password; // SMTP password
      $mail->SMTPSecure = $settings->SMTPSecure; // Enable TLS encryption, `ssl` also accepted
      $mail->Port = $settings->port; // TCP port to connect to
    }

    return $mail;
  };
  $app = new \Slim\App($container);
}

\Nope\Utils::scanAndInclude([NOPE_LIB_DIR . 'register']);

if(!$isNopeEmbedded) {
  \Nope::registerRoute(NOPE_LIB_DIR . 'routes/install.php');
  \Nope::registerRoute(NOPE_LIB_DIR . 'routes/index.php');
  \Nope::registerRoute(NOPE_LIB_DIR . 'routes/view.php');
  \Nope::registerRoute(NOPE_LIB_DIR . 'routes/service.php');
}

require NOPE_LIB_DIR . 'models/Content.php';
require NOPE_LIB_DIR . 'models/TextContent.php';
require NOPE_APP_DIR . 'bootstrap.php';

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
}

// import the Intervention Image Manager Class
use Intervention\Image\ImageManagerStatic as Image;
Image::configure([
  'cache' => [
    'path' => NOPE_CACHE_DIR . 'uploads'
  ]
]);

if(!$isNopeEmbedded) {
  foreach(\Nope::getConfig('nope.routes') as $file) {
    if(file_exists($file)) {
      require $file;
    }
  }
  $app->add(new Nope\Middleware\Install());
  $app->run();
}
