<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/content/gallery', function() {

  $this->get('', function($request, $response) {
    $rpp = 5;
    $currentUser = User::getAuthenticated();
    if(!$currentUser->can('gallery.read')) {
      return $response->withStatus(403);
    }
    $queryParams = (object) $request->getQueryParams();
    $params = Utils::getPaginationTerms($request, $rpp);
    $contentsList = Gallery::findAll([
      'text' => $params->query
    ], $params->limit, $params->offset, $count);
    $metadata = Utils::getPaginationMetadata($params->page, $count, $rpp);
    return $response->withJson([
      'currentUser' => $currentUser,
      'metadata' => $metadata,
      'data' => $contentsList
    ])->withHeader('Link', json_encode($metadata));
  });

  $this->post('', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.create')) {
      $fields = ['title', 'body', 'slug', 'starred', 'priority'];
      if($currentUser->can('media.read')) {
        $fields[] = 'cover';
        $fields[] = 'media';
      }
      $contentToCreate = new Gallery();
      $body = $request->getParsedBody();
      $contentToCreate->import($body, $fields);
      $contentToCreate->setAuthor($currentUser);
      try {
        $contentToCreate->save();
        if($body['tags']) {
          $contentToCreate->setTags($body['tags']);
          $contentToCreate->save();
        }
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
    if($currentUser->can('gallery.read')) {
      $content = Gallery::findById($args['id']);
    }
    return $response->withJson(['currentUser' => $currentUser, "data" => $content]);
  });

  $this->put('/{id}', function($request, $response, $args) {
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

  $this->delete('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('gallery.delete')) {
      $contentToDelete = new Gallery($args['id']);
      $contentToDelete->delete();
    }
    return $response->withJson(['currentUser' => $currentUser]);
  });

});
