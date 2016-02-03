<?php

// register models
\Nope::registerModel('user', [
  'model' => NOPE_LIB_DIR . 'models/User.php',
  'route' => [
    NOPE_LIB_DIR . 'routes/auth.php',
    NOPE_LIB_DIR . 'routes/user.php'
  ],
  'js' => [
    'lib/assets/js/app.js',
    'lib/assets/js/ui.js',
    'lib/assets/js/content.js'
  ]
]);
\Nope::registerModel('setting', [
  'model' => [
    NOPE_LIB_DIR . 'models/Setting.php',
    NOPE_LIB_DIR . 'queries/Setting.php'
  ],
  'route' => NOPE_LIB_DIR . 'routes/setting.php',
  'js' => [
    'lib/assets/js/setting.js'
  ]
]);
\Nope::registerModel('page', [
  'model' => [
    NOPE_LIB_DIR . 'models/Page.php',
    NOPE_LIB_DIR . 'queries/Page.php'
  ],
  'route' => NOPE_LIB_DIR . 'routes/page.php'
]);
\Nope::registerModel('media', [
  'model' => [
    NOPE_LIB_DIR . 'models/Media.php',
    NOPE_LIB_DIR . 'queries/Media.php'],
  'route' => NOPE_LIB_DIR . 'routes/media.php',
  'js' => [
    'lib/assets/js/media.js'
  ]
]);
\Nope::registerModel('gallery', [
  'model' => NOPE_LIB_DIR . 'models/Gallery.php',
  'route' => NOPE_LIB_DIR . 'routes/gallery.php',
  'js' => [
    'lib/assets/js/gallery.js'
  ]
]);
