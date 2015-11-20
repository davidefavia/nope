<?php

define('NOPE_DIR', realpath(__DIR__ . '/nope/') . DIRECTORY_SEPARATOR);
define('NOPE_LIB_DIR', realpath(__DIR__ . '/nope/lib/') . DIRECTORY_SEPARATOR);
define('NOPE_INDEX', realpath(__DIR__) . DIRECTORY_SEPARATOR);
define('NOPE_PATH', '/' . basename(dirname($_SERVER['SCRIPT_NAME'])) . '/nope/');

require NOPE_DIR . 'lib/bootstrap.php';
