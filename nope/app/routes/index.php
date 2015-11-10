<?php

$app->get('/', function ($req, $res) {
  return $this->view->render($res, 'index.php', ['name' => 'John']);
});
