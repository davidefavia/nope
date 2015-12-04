<?php

// Register user roles here.

\Nope::registerRole('editor', [
  'label' => 'Editor',
  'permissions' => [
    'page.*'
  ]
]);
