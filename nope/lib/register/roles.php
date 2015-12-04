<?php

// register roles
\Nope::registerRole('admin', [
  'label' => 'Admin',
  'permissions' => ['*.*']
]);
