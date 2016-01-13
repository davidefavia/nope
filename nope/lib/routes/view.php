<?php

namespace Nope;

$app->group(NOPE_ADMIN_ROUTE . '/view', function() {

  $this->get('/{view:[a-zA-Z0-9-_\/]+}{ext:\.html}', function ($request, $response, $args) {
    if($args['view'] === 'app') {
      $data['menuItems'] = \Nope::getConfig('nope.admin.menu');
    }
    return $this->view->adminRender($response, $args['view'] . '.php', $data);
  });

});
