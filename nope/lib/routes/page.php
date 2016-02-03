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
    $queryParams = (object) $request->getQueryParams();
    $params = Utils::getPaginationTerms($request, $rpp);
    $contentsList = Page::findAll([
      'text' => $params->query,
      'status' => $queryParams->status,
      'excluded' => explode(',', $queryParams->excluded)
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
    if($currentUser->can('page.create')) {
      $fields = ['title', 'body', 'slug', 'startPublishingDate', 'endPublishingDate', 'status', 'summary', 'format', 'starred', 'priority'];
      if($currentUser->can('media.read')) {
        $fields[] = 'cover';
      }
      if($currentUser->can('page.custom')) {
        $fields[] = 'custom';
      }
      $contentToCreate = new Page();
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
      return $response->withJson(['currentUser' => $currentUser, 'data' => $contentToCreate]);
    } else {
      return $response->withStatus(403);
    }
  });

  $this->get('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.read')) {
      $content = Page::findById($args['id']);
    }
    return $response->withJson(['currentUser' => $currentUser, 'data' => $content]);
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
    return $response->withJson(['currentUser' => $currentUser, 'data' => $content]);
  });

  $this->put('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('page.update')) {
      $fields = ['title', 'body', 'slug', 'startPublishingDate', 'endPublishingDate', 'status', 'summary', 'tags', 'format', 'starred', 'priority'];
      if($currentUser->can('media.read')) {
        $fields[] = 'cover';
      }
      if($currentUser->can('page.custom')) {
        $fields[] = 'custom';
      }
      $contentToUpdate = new Page($args['id']);
      if($contentToUpdate) {
        $body = $request->getParsedBody();
        $contentToUpdate->import($body, $fields);
        $contentToUpdate->save();
        return $response->withJson(['currentUser' => $currentUser, 'data' => $contentToUpdate]);
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
    return $response->withJson(['currentUser' => $currentUser]);
  });

});
