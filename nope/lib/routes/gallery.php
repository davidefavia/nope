<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/content/gallery', function() {

  $this->get('', function($request, $response) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.read')) {
      $contentsList = Gallery::findAll();
    } else {
      return $response->withStatus(403);
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $contentsList]);
  });

  $this->post('', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.create')) {
      if($currentUser->can('media.read')) {
        $fields = ['title', 'description', 'cover', 'tags', 'media'];
      } else {
        $fields = ['title', 'description', 'tags'];
      }
      $contentToCreate = new Gallery();
      $body = $request->getParsedBody();
      $contentToCreate->import($body, $fields);
      $contentToCreate->setAuthor($currentUser);
      $contentToCreate->save();
      return $response->withJson(['currentUser' => $currentUser, "data" => $contentToCreate]);
    } else {
      return $response->withStatus(403);
    }
  });

  $this->get('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.read')) {
      $content = Gallery::findById($args['id']);
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $content]);
  });

  $this->put('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    $body = $request->getParsedBody();
    if($currentUser->can('gallery.update')) {
      if($currentUser->can('media.read')) {
        $fields = ['title', 'description', 'tags', 'cover', 'media'];
      } else {
        $fields = ['title', 'description', 'tags'];
      }
      $contentToUpdate = new Gallery($args['id']);
      if($contentToUpdate) {
        $contentToUpdate->import($body, $fields);
        $contentToUpdate->save();
        return $response->withJson(['currentUser' => $currentUser, "data" => $contentToUpdate]);
      } else {
        return $response->withStatus(404);
      }
    } else {
      return $response->withStatus(403);
    }
  });

  $this->delete('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.delete')) {
      $contentToDelete = new Gallery($args['id']);
      $contentToDelete->delete();
    }
    return $response->withJson(['currentUser' => $currentUser]);
  });

});
