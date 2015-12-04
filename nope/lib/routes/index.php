<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE, function() {

  $this->get('', function ($req, $res) {
    return redirect($req, $res, NOPE_ADMIN_ROUTE . '/');
  });

  $this->get('/', function ($req, $res) {
    foreach(\Nope::getConfig('nope.roles') as $key => $value) {
      $roles[] = [
        'label' => $value['label'],
        'key' => $key
      ];
    }
    $models = \Nope::getConfig('nope.models');
    $jsFiles = [];
    foreach($models as $name => $definition) {
      if($definition['js']) {
        if(is_array($definition['js'])) {
          foreach($definition['js'] as $file) {
            $jsFiles[] = $file;
          }
        } else {
          $jsFiles[] = $definition['js'];
        }
      }
    }
    return $this->view->adminRender($res, 'index.php', ['request' => $req, 'roles' => $roles, 'js' => $jsFiles]);
  });

});
