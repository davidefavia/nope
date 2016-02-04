<?php

namespace Nope;

use \Stringy\StaticStringy as S;

$app->group(NOPE_ADMIN_ROUTE . '/view', function() {

  $this->get('/{view:[a-zA-Z0-9-_\/]+}{ext:\.html}', function ($request, $response, $args) {
    if($args['view'] === 'app') {
      $data['menuItems'] = \Nope::getConfig('nope.admin.menu');
    } elseif (S::startsWith($args['view'], 'setting/detail/')) {
      $p = explode('/', $args['view']);
      $settingKey = $p[count($p)-1];
      foreach (\Nope::getConfig('nope.settings') as $key => $value) {
        if($value->settingkey === $settingKey) {
          $data['setting'] = $value;
          $args['view'] = 'setting/detail';
        }
      }
    } elseif (S::startsWith($args['view'], 'directive/model/')) {
      $p = explode('/', $args['view']);
      $data['templateName'] = $p[count($p)-1];
      $args['view'] = 'directive/model';
    }
    return $this->view->adminRender($response, $args['view'] . '.php', $data);
  });

});
