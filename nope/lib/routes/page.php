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

  $this->post('', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.create')) {
      $fields = ['title', 'body', 'slug', 'startPublishingDate', 'endPublishingDate', 'status'];
      if($currentUser->can('media.read')) {
        $fields[] = 'cover';
      }
      $contentToCreate = new Page();
      $body = $request->getParsedBody();
      $contentToCreate->import($body, $fields);
      $contentToCreate->setAuthor($currentUser);
      try {
        $contentToCreate->save();
      } catch(\Exception $e) {
        // Conflict with existing slug!
        return $response->withStatus(409, $e->getMessage());
      }
      return $response->write(json_encode(['currentUser' => $currentUser, "data" => $contentToCreate]));
    } else {
      return $response->withStatus(403);
    }
  });

  $this->get('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.read')) {
      $content = Page::findById($args['id']);
    }
    return $response->write(json_encode(['currentUser' => $currentUser, "data" => $content]));
  });

  $this->get('/{id}/status', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.read')) {
      $params = $request->getQueryParams();
      $fields = ['startPublishingDate', 'endPublishingDate', 'status'];
      $content = Page::findById($args['id']);
      if($content) {
        $content->import($params, $fields);
      }
    }
    return $response->write(json_encode(['currentUser' => $currentUser, "data" => $content]));
  });

  $this->put('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    $body = $request->getParsedBody();
    if($currentUser->can('page.update')) {
      $fields = ['title', 'body', 'slug', 'startPublishingDate', 'endPublishingDate', 'status'];
      if($currentUser->can('media.read')) {
        $fields[] = 'cover';
      }
      $contentToUpdate = new Page($args['id']);
      if($contentToUpdate) {
        $contentToUpdate->import($body, $fields);
        $contentToUpdate->save();
        $body = $response->getBody();
        $body->write(json_encode(['currentUser' => $currentUser, "data" => $contentToUpdate]));
        return $response->withBody($body);
      } else {
        return $response->withStatus(404);
      }
    } else {
      return $response->withStatus(403);
    }
  });

  $this->delete('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.delete')) {
      $contentToDelete = new Page($args['id']);
      $contentToDelete->delete();
    }
    return $response->write(json_encode(['currentUser' => $currentUser]));
  });

});
