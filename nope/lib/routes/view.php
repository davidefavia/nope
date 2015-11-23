<?php

$app->group(NOPE_ADMIN_ROUTE . '/view', function() {

  $this->get('/{view:[a-zA-Z0-9-_]+}{ext:\.html}', function ($req, $res, $args) {
    if($args['view'] === 'app') {
      $data['menuItems'] = \Nope::getConfig('nope.admin.menu');
    }
    return $this->view->adminRender($res, $args['view'] . '.php', $data);
  });

});
