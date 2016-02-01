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
    $settingsList = \Nope::getSettings();
    #var_dump($settingsList);
    #die();
    return $response->withJson([
      'currentUser' => $currentUser,
      'data' => $settingsList
    ]);
  });

  $this->get('/{key}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('setting.read')) {
      $content = Setting::findByKey($args['key']);
      if(!$content) {
        $settingsList = \Nope::getSettings();
        foreach ($settingsList as $key => $value) {
          if($value->settingkey === $args['key']) {
            $content = new Setting();
            $content->settingkey = $args['key'];
            $content->save();
          }
        }
      }
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $content]);
  });

  $this->put('/{key}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('setting.update')) {
      $fields = ['settingkey', 'value'];
      try {
        $contentToUpdate = Setting::findByKey($args['key']);
        if($contentToUpdate) {
          $body = $request->getParsedBody();
          $contentToUpdate->import($body, $fields);
          $contentToUpdate->save();
          return $response->withJson(['currentUser' => $currentUser, "data" => $contentToUpdate]);
        } else {
          return $response->withStatus(404);
        }
      } catch(\Exception $e) {
        // Validation exception.
        return $response->withStatus(400, $e->getMessage());
      }
    } else {
      return $response->withStatus(403);
    }
  });

});
