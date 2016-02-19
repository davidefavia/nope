<?php

\Nope::registerMenuItem([
  'id' => 'dashboard',
  'label' => 'Dashboard',
  'permission' => '',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/dashboard\')!==-1',
  'icon' => 'fa fa-dashboard fa-fw',
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
  'icon' => 'fa fa-file-text-o fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.content({contentType:\'page\'})',
    'ui-sref-opts' => '{reload: true}'
  ]
], 50);

\Nope::registerMenuItem([
  'id' => 'media',
  'label' => 'Media',
  'permission' => 'media.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/media\')!==-1',
  'icon' => 'fa fa-picture-o fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.media',
    'ui-sref-opts' => '{reload: true}'
  ]
], 75);

\Nope::registerMenuItem([
  'id' => 'gallery',
  'label' => 'Galleries',
  'permission' => 'gallery.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/gallery\')!==-1',
  'icon' => 'fa fa-object-group fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.gallery',
    'ui-sref-opts' => '{reload: true}'
  ]
], 100);

\Nope::registerMenuItem([
  'id' => 'menu',
  'label' => 'Menus',
  'permission' => 'menu.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/menu\')!==-1',
  'icon' => 'fa fa-bars fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.menu',
    'ui-sref-opts' => '{reload: true}'
  ]
], 125);

\Nope::registerMenuItem([
  'id' => 'user',
  'label' => 'Users',
  'permission' => 'user.read',
  'role' => '',
  'activeWhen' => 'selectedPath.indexOf(\'/user\')!==-1',
  'icon' => 'fa fa-user fa-fw',
  'attrs' => [
    'href' => '',
    'ui-sref' => 'app.user',
    'ui-sref-opts' => '{reload: true}'
  ]
], 150);

\Nope::registerMenuItem([
  'id' => 'setting',
  'label' => 'Settings',
  'permission' => '',
  'role' => 'admin',
  'activeWhen' => 'selectedPath.indexOf(\'/setting\')!==-1',
  'icon' => 'fa fa-gears',
  'attrs' => [
    'href' => '#/setting'
  ]
], 200);

\Nope::registerMenuItem([
  'id' => 'logout',
  'label' => 'Logout',
  'permissions' => '',
  'role' => '',
  'icon' => 'fa fa-unlock fa-fw',
  'attrs' => [
    'href' => '',
    'ng-click' => 'logout();'
  ]
], 300);
