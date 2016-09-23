<?php

define('NOPE_DATETIME_TIMEZONE', '');
define('NOPE_SECURITY_SALT', '');
define('NOPE_THEME', 'default');

if(in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])){
  define('NOPE_DEVELOPMENT', true);
}

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
