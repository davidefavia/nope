<?php

namespace Nope;

use \Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/setting', function() {

  $this->get('', function($request, $response) {
    $rpp = 5;
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('setting.read')) {
      return $response->withStatus(403);
    }
    $settingsList = \Nope::getConfig('nope.settings');
    return $response->withJson([
      'currentUser' => $currentUser,
      'data' => $settingsList
    ]);
  });

  $this->get('/{key}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('setting.read')) {
      $content = Setting::findByKey($args['key']);
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $content]);
  });

  $this->put('/{key}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.update')) {
      $fields = ['title', 'body', 'tags', 'slug', 'starred', 'priority'];
      if($currentUser->can('media.read')) {
        $fields[] = 'cover';
        $fields[] = 'media';
      }
      try {
        $contentToUpdate = new Gallery($args['id']);
        if($contentToUpdate) {
          $body = $request->getParsedBody();
          $contentToUpdate->import($body, $fields);
          $contentToUpdate->save();
          return $response->withJson(['currentUser' => $currentUser, "data" => $contentToUpdate]);
        } else {
          return $response->withStatus(404);
        }
      } catch(\Exception $e) {
        // Conflict with existing slug!
        return $response->withStatus(409, $e->getMessage());
      }
    } else {
      return $response->withStatus(403);
    }
  });

});
