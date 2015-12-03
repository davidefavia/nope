<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/content/gallery', function() {

  $this->get('', function($req, $res) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.read')) {
      $contentsList = Gallery::findAll();
    } else {
      return $res->withStatus(403);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $contentsList]));
    return $res->withBody($body);
  });

  $this->post('', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.create')) {
      if($currentUser->can('media.read')) {
        $fields = ['title', 'description', 'cover', 'tags', 'media'];
      } else {
        $fields = ['title', 'description', 'tags'];
      }
      $contentToCreate = new Gallery();
      $body = $req->getParsedBody();
      $contentToCreate->import($body, $fields);
      $contentToCreate->setAuthor($currentUser);
      $contentToCreate->save();
      $body = $res->getBody();
      $body->write(json_encode(['currentUser' => $currentUser, "data" => $contentToCreate]));
      return $res->withBody($body);
    } else {
      return $res->withStatus(403);
    }
  });

  $this->get('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.read')) {
      $content = Gallery::findById($args['id']);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $content]));
    return $res->withBody($body);
  });

  $this->put('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    $body = $req->getParsedBody();
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
        $body = $res->getBody();
        $body->write(json_encode(['currentUser' => $currentUser, "data" => $contentToUpdate]));
        return $res->withBody($body);
      } else {
        return $res->withStatus(404);
      }
    } else {
      return $res->withStatus(403);
    }
  });

  $this->delete('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.delete')) {
      $contentToDelete = new Gallery($args['id']);
      $contentToDelete->delete();
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser]));
    return $res->withBody($body);
  });

});
