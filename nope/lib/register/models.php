<?php

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
