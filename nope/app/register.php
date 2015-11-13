<?php

// Routing
\Nope::registerRoute(NOPE_DIR . 'app/routes/index.php');

\Nope::registerRole('editor', [
  'label' => 'Editor',
  'permissions' => [
    'profile.read',
    'profile.update'
  ]
]);
