<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/content/page', function() {

  $this->get('', function($req, $res) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.read')) {
      $contentsList = Page::findAll();
    } else {
      return $res->withStatus(403);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $contentsList]));
    return $res->withBody($body);
  });

  $this->post('', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.create')) {
      $fields = ['title', 'body', 'slug'];
      $contentToCreate = new Page();
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
    if($currentUser->can('page.read')) {
      $content = Page::findById($args['id']);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $content]));
    return $res->withBody($body);
  });

  $this->put('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    $body = $req->getParsedBody();
    if($currentUser->can('page.update')) {
      $fields = ['title', 'body', 'slug'];
      $contentToUpdate = new Page($args['id']);
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
    if($currentUser->can('page.delete')) {
      $contentToDelete = new Page($args['id']);
      $contentToDelete->delete();
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser]));
    return $res->withBody($body);
  });

});
