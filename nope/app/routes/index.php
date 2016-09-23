<?php

namespace Nope;

$app->group('/', function() {

  $this->get('', function ($request, $response) {
    $currentUser = User::getAuthenticated();
    $params = (object) $request->getQueryParams();
    $setting = getSetting('nope');
    $themeSetting = getSetting('theme');
    $page = Query\Page::findBySlug($setting->homepage->slug);
    if($page && ((string) $page->realStatus === 'published' || ($currentUser && (int) $params->preview === 1 && $currentUser->isAdmin()))) {
      return $this->view->render($response, 'index.php', [
        'content' => $page,
        'setting' => $setting->value,
        'themeSetting' => $themeSetting
      ]);
    } else {
      return $this->view->render($response->withStatus(404), '404.php');
    }
  });

  $this->get('{slug:[a-zA-Z0-9-_\/]+}', function ($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    $params = (object) $request->getQueryParams();
    $page = Query\Page::findBySlug($args['slug']);
    if($page && ((string) $page->realStatus === 'published' || ($currentUser && (int) $params->preview === 1 && $currentUser->isAdmin()))) {
      return $this->view->render($response, 'page.php', [
        'content' => $page
      ]);
    } else {
      return $this->view->render($response->withStatus(404), '404.php');
    }
  });

});
