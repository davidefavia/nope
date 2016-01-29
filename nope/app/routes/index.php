<?php

namespace Nope;

$app->get('/', function ($request, $response) {
  $setting = Query\Setting::findByKey('nope');
  $page = Query\Page::findBySlug('home');
  if($page && (string) $page->status === 'published') {
    return $this->view->render($response, 'index.php', [
      'content' => $page,
      'setting' => $setting->value
    ]);
  } else {
    return $response->withStatus(404);
  }
});

$app->get('/{slug:[a-zA-Z0-9-_\/]+}', function ($request, $response, $args) {
  $page = Query\Page::findBySlug($args['slug']);
  if($page && (string) $page->status === 'published') {
    return $this->view->render($response, 'page.php', [
      'content' => $page
    ]);
  } else {
    return $response->withStatus(404);
  }
});
