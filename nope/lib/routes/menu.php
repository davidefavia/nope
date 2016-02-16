<?php

namespace Nope;

$app->group(NOPE_ADMIN_ROUTE . '/menu', function() {

  $this->get('', function($request, $response) {
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('menu.read')) {
      return $response->withStatus(403);
    }
    $contentsList = Menu::findAll();
    return $response->withJson([
      'currentUser' => $currentUser,
      'data' => $contentsList
    ]);
  });

  $this->post('', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('menu.create')) {
      $fields = ['title', 'body', 'slug'];
      $contentToCreate = new Menu();
      $body = $request->getParsedBody();
      $contentToCreate->import($body, $fields);
      $contentToCreate->setItems($body['items']);
      $contentToCreate->setAuthor($currentUser);
      try {
        $contentToCreate->save();
      } catch(\Exception $e) {
        // Conflict with existing slug!
        return $response->withStatus(409, $e->getMessage());
      }
      return $response->withJson(['currentUser' => $currentUser, "data" => $contentToCreate]);
    } else {
      return $response->withStatus(403);
    }
  });

  $this->get('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('menu.read')) {
      $content = Menu::findById($args['id']);
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $content]);
  });

  $this->put('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('menu.update')) {
      $fields = ['title', 'body', 'slug'];
      try {
        $contentToUpdate = new Menu($args['id']);
        if($contentToUpdate) {
          $body = $request->getParsedBody();
          $contentToUpdate->import($body, $fields);
          $contentToUpdate->setItems($body['items']);
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

  $this->delete('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('menu.delete')) {
      $contentToDelete = new Menu($args['id']);
      $contentToDelete->delete();
    }
    return $response->withJson(['currentUser' => $currentUser]);
  });

});
