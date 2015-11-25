<?php

namespace Nope;

$app->get('/', function ($req, $res) {
  return $this->view->render($res, 'index.php', ['name' => 'John']);
});

$app->get('/{slug:[a-zA-Z0-9-_\/]+}', function ($req, $res, $args) {
  $page = Page::findBySlug($args['slug']);
  if($page) {
    return $this->view->render($res, 'page.php', ['content' => $page]);
  } else {
    return $res->withStatus(404);
  }
});
