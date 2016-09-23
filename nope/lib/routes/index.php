<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE, function() {

  $this->get('', function ($request, $response) {
    return redirect($request, $response, NOPE_ADMIN_ROUTE . '/');
  });

  $this->get('/', function ($request, $response) {
    $userRoles = [];
    foreach(\Nope::getConfig('nope.user.roles') as $key => $value) {
      $userRoles[] = [
        'label' => $value['label'],
        'key' => $key
      ];
    }
    $textFormats = [];
    foreach(\Nope::getConfig('nope.content.format') as $key => $value) {
      $textFormats[] = [
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
    $params = (object) $request->getQueryParams();
    $isIframe = ((int) $params->iframe === 1?'true':'false');
    $defaultTextFormat = \Nope::getConfig('nope.content.format.default')?:null;
    return $this->view->adminRender($response, 'index.php', [
      'request' => $request,
      'userRoles' => $userRoles,
      'textFormats' => $textFormats,
      'defaultTextFormat' => $defaultTextFormat,
      'js' => $jsFiles,
      'isIframe' => $isIframe
    ]);
  });

});
