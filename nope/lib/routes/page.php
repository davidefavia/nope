<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/content/page', function() {

  $this->get('', function($request, $response) {
    $rpp = 5;
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('page.read')) {
      return $response->withStatus(403);
    }
    $params = Utils::getPaginationTerms($request, $rpp);
    $contentsList = Page::findAll([
      'text' => $params->query
    ], $params->limit, $params->offset, $count);
    $metadata = Utils::getPaginationMetadata($params->page, $count, $rpp);
    return $response->write(json_encode([
      'currentUser' => $currentUser,
      'metadata' => $metadata,
      'data' => $contentsList
    ]))->withHeader('Link', json_encode($metadata));
  });

  $this->post('', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.create')) {
      $fields = ['title', 'body', 'slug', 'startPublishingDate', 'endPublishingDate', 'status'];
      if($currentUser->can('media.read')) {
        $fields[] = 'cover';
      }
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
      $fields = ['title', 'body', 'slug', 'startPublishingDate', 'endPublishingDate', 'status'];
      if($currentUser->can('media.read')) {
        $fields[] = 'cover';
      }
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
