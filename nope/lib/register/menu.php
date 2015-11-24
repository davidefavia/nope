<?php

\Nope::registerMenuItem([
  'id' => 'dashboard',
  'label' => 'Dashboard',
  'permission' => '',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/dashboard\')!==-1',
  'icon' => 'fa fa-dashboard',
  'attrs' => [
    'href' => '#/dashboard'
  ]
], 0);

\Nope::registerMenuItem([
  'id' => 'page',
  'label' => 'Pages',
  'permission' => 'page.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/page\')!==-1',
  'icon' => 'fa fa-file-text-o',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.content({contentType:\'page\'})',
    'ui-sref-opts' => '{reload: true}'
  ]
], 50);

\Nope::registerMenuItem([
  'id' => 'user',
  'label' => 'Users',
  'permission' => 'user.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/user\')!==-1',
  'icon' => 'fa fa-user',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.user',
    'ui-sref-opts' => '{reload: true}'
  ]
], 100);

\Nope::registerMenuItem([
  'id' => 'setting',
  'label' => 'Settings',
  'permission' => '',
  'role' => 'admin',
  'activeWhen' => 'selectedPath.indexOf(\'/setting\')!==-1',
  'icon' => 'fa fa-gear',
  'attrs' => [
    'href' => '#/setting'
  ]
], 200);

\Nope::registerMenuItem([
  'id' => 'logout',
  'label' => 'Logout',
  'permissions' => '',
  'role' => '',
  'icon' => 'fa fa-sign-out',
  'attrs' => [
    'href' => '',
    'ng-click' => 'logout();'
  ]
], 300);
