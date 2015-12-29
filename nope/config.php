<?php

define('NOPE_DATETIME_TIMEZONE', '');
define('NOPE_SECURITY_SALT', '');
define('NOPE_ADMIN_ROUTE', '/admin');
define('NOPE_DATABASE_PATH', NOPE_INDEX . 'nope.db');
define('NOPE_THEME', 'default');

// Configure PHPMailer
\Nope::setConfig('nope.mailer', (object) [
  'sender' => (object) [
    'name' => '',
    'email' => ''
  ],
  'useSMTP' => true,
  'host' => '',
  'SMTPAuth' => true,
  'username' => '',
  'password' => '',
  'SMTPSecure' => 'tls',
  'port' => 587
]);
