<?php

namespace Nope;

$app->get('/', function ($request, $response) {
  $setting = Query\Setting::findByKey('nope');
  $themeSetting = Query\Setting::findByKey('theme');
  $page = Query\Page::findBySlug($setting->value->homepage->slug);
  if($page && (string) $page->realStatus === 'published') {
    return $this->view->render($response, 'index.php', [
      'content' => $page,
      'setting' => $setting->value,
      'themeSetting' => $themeSetting->value
    ]);
  } else {
    return $this->view->render($response->withStatus(404), '404.php');
  }
});

$app->get('/{slug:[a-zA-Z0-9-_\/]+}', function ($request, $response, $args) {
  $page = Query\Page::findBySlug($args['slug']);
  if($page && (string) $page->status === 'published') {
    return $this->view->render($response, 'page.php', [
      'content' => $page
    ]);
  } else {
    return $this->view->render($response->withStatus(404), '404.php');
  }
});
