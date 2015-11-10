<?php

$app->group(NOPE_ADMIN_ROUTE, function() {

  $this->get('', function ($req, $res) {
    return $this->view->adminRender($res, 'index.php', ['request' => $req]);
  });

  $this->get('/', function ($req, $res) {
    return $this->view->adminRender($res, 'index.php', ['request' => $req]);
  });

});
