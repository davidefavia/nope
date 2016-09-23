<?php

\Nope::registerMenuItem('dashboard', [
  'label' => 'Dashboard',
  'permission' => '',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/dashboard\')!==-1',
  'icon' => 'fa fa-dashboard fa-fw',
  'attrs' => [
    'href' => '#/dashboard'
  ],
  'priority' => 1000
]);

\Nope::registerMenuItem('page', [
  'label' => 'Pages',
  'permission' => 'page.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/page\')!==-1',
  'icon' => 'fa fa-file-text-o fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.content({contentType:\'page\'})',
    'ui-sref-opts' => '{reload: true}'
  ],
  'priority' => 900
]);

\Nope::registerMenuItem('media', [
  'label' => 'Media',
  'permission' => 'media.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/media\')!==-1',
  'icon' => 'fa fa-picture-o fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.media',
    'ui-sref-opts' => '{reload: true}'
  ],
  'priority' => 800
]);

\Nope::registerMenuItem('gallery', [
  'label' => 'Galleries',
  'permission' => 'gallery.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/gallery\')!==-1',
  'icon' => 'fa fa-object-group fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.gallery',
    'ui-sref-opts' => '{reload: true}'
  ],
  'priority' => 700
]);

\Nope::registerMenuItem('menu', [
  'label' => 'Menus',
  'permission' => 'menu.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/menu\')!==-1',
  'icon' => 'fa fa-bars fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.menu',
    'ui-sref-opts' => '{reload: true}'
  ],
  'priority' => 600
]);

\Nope::registerMenuItem('user', [
  'label' => 'Users',
  'permission' => '',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/user\')!==-1',
  'icon' => 'fa fa-user fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.user',
    'ui-sref-opts' => '{reload: true}'
  ],
  'priority' => 500
]);

\Nope::registerMenuItem('setting', [
  'label' => 'Settings',
  'permission' => '',
  'role' => 'admin',
  'activeWhen' => 'selectedPath.indexOf(\'/setting\')!==-1',
  'icon' => 'fa fa-gears',
  'attrs' => [
    'href' => '#/setting'
  ],
  'priority' => 400
]);

\Nope::registerMenuItem('logout', [
  'label' => 'Logout',
  'permissions' => '',
  'role' => '',
  'icon' => 'fa fa-unlock fa-fw',
  'attrs' => [
    'href' => '',
    'ng-click' => 'logout();'
  ],
  'priority' => -1000
]);
