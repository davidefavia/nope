<?php

// Register user roles here.

\Nope::registerRole('editor', [
  'label' => 'Editor',
  'permissions' => [
    'page.*',
    'media.*',
    'gallery.*',
    // Extend permissions here below
  ]
]);

\Nope::registerRole('subscriber', [
  'label' => 'Subscriber',
  'permissions' => [
    // Extend permissions here below
  ]
]);
