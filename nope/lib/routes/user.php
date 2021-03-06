<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/user', function() {

  $this->get('', function($request, $response) {
    $rpp = NOPE_USER_RPP;
    $currentUser = User::getAuthenticated();
    $params = Utils::getPaginationTerms($request, $rpp);
    $queryParams = (object) $request->getQueryParams();
    if($currentUser->can('user.read')) {
      $usersList = User::findAll([
        'excluded' => explode(',', $queryParams->excluded),
        'text' => $params->query
      ], $params->limit, $params->offset, $count);
    } else {
      $usersList = [$currentUser];
      $count = 1;
    }
    $metadata = Utils::getPaginationMetadata($params->page, $count, $rpp);
    return $response->withJson([
      'currentUser' => $currentUser,
      'metadata' => $metadata,
      'data' => $usersList
    ])->withHeader('Link', json_encode($metadata));
  });

  $this->post('', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('user.create')) {
      $fields = ['username','email','description','enabled','prettyName','role'];
      $userToCreate = new User();
      $body = $request->getParsedBody();
      if(v::identical($body['password'])->validate($body['confirm']) && $body['role']!=='admin') {
        if($currentUser->can('user.custom')) {
          $fields[] = 'custom';
        }
        $userToCreate->import($body, $fields);
        $userToCreate->setPassword($body['password']);
        try {
          $userToCreate->save();
        } catch(\Exception $e) {
          if(get_class($e)==='Exception') {
            // Conflict: existing username
            $code = 409;
          } else {
            // Validation exception
            $code = 400;
          }
          return $response->withStatus($code, $e->getMessage());
        }
        return $response->withJson(['currentUser' => $currentUser, "data" => $userToCreate]);
      } else {
        return $response->withStatus(400);
      }
    } else {
      return $response->withStatus(403);
    }
  });

  $this->get('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('user.read') || $currentUser->id === $args['id']) {
      $user = User::findById($args['id']);
    }
    return $response->write(json_encode(['currentUser' => $currentUser, "data" => $user]));
  });

  $this->put('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    $body = $request->getParsedBody();
    if($currentUser->can('user.update') || $currentUser->id == $args['id']) {
      if($currentUser->isAdmin()) {
        if($currentUser->id == $args['id']) {
          $fields = ['email','description','prettyName','cover', 'custom'];
        } else {
          if($body['role'] === 'admin') {
            return $response->withStatus(400);
          } else {
            $fields = ['email','description','enabled','prettyName','role','cover', 'custom'];
          }
        }
      } else {
        if($currentUser->can('media.read')) {
          $fields = ['email','description','prettyName','cover'];
        } else {
          $fields = ['email','description','prettyName'];
        }
        if($currentUser->can('user.custom')) {
          $fields[] = 'custom';
        }
      }
      $userToUpdate = new User($args['id']);
      if($userToUpdate) {
        $userToUpdate->import($body, $fields);
        if($body['password'] && $body['confirm'] && v::identical($body['password'])->validate($body['confirm'])) {
          $userToUpdate->setPassword($body['password']);
        }
        $userToUpdate->save();
        if($currentUser->id == $userToUpdate->id) {
          $userToUpdate->saveInSession();
          $currentUser = $userToUpdate;
        }
        return $response->withJson(['currentUser' => $currentUser, "data" => $userToUpdate]);
      } else {
        return $response->withStatus(404);
      }
    } else {
      return $response->withStatus(403);
    }
  });

  $this->delete('/{id}', function($request, $response, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('user.delete')) {
      $userToDelete = new User($args['id']);
      if($userToDelete && $currentUser->id === $userToDelete->id) {
        return $response->withStatus(403);
      } else {
        $userToDelete->delete();
      }
    }
    return $response->withJson(['currentUser' => $currentUser]);
  });

})->add(auth());
