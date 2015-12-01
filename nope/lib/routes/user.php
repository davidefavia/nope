<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/user', function() {

  $this->get('', function($req, $res) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('user.read')) {
      $usersList = User::findAll();
    } else {
      $usersList = [$currentUser];
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $usersList]));
    return $res->withBody($body);
  });

  $this->post('', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('user.create')) {
      $fields = ['username','email','description','enabled','pretty_name','role'];
      $userToCreate = new User();
      $body = $req->getParsedBody();
      if(v::identical($body['password'])->validate($body['confirm']) && $body['role']!=='admin') {
        $userToCreate->import($body, $fields);
        $userToCreate->setPassword($body['password']);
        $userToCreate->save();
        if($currentUser->id == $userToCreate->id) {
          $userToCreate->saveInSession();
          $currentUser = $userToCreate;
        }
        $body = $res->getBody();
        $body->write(json_encode(['currentUser' => $currentUser, "data" => $userToCreate]));
        return $res->withBody($body);
      } else {
        return $res->withBody(400);
      }
    } else {
      return $res->withStatus(403);
    }
  });

  $this->get('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    if($currentUser->can('user.read') || $currentUser->id === $args['id']) {
      $user = User::findById($args['id']);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $user]));
    return $res->withBody($body);
  });

  $this->put('/{id}', function($req, $res, $args) {
    $currentUser = User::getAuthenticated();
    $body = $req->getParsedBody();
    if($currentUser->can('user.update') || $currentUser->id == $args['id']) {
      if($currentUser->isAdmin()) {
        if($currentUser->id == $args['id']) {
          $fields = ['email','description','pretty_name','cover'];
        } else {
          if($body['role'] === 'admin') {
            return $res->withStatus(400);
          } else {
            $fields = ['email','description','enabled','pretty_name','role','cover'];
          }
        }
      } else {
        if($currentUser->can('media.read')) {
          $fields = ['email','description','pretty_name','cover'];
        } else {
          $fields = ['email','description','pretty_name'];
        }
      }
      $userToUpdate = new User($args['id']);
      if($userToUpdate) {
        $userToUpdate->import($body, $fields);
        $userToUpdate->save();
        if($currentUser->id == $userToUpdate->id) {
          $userToUpdate->saveInSession();
          $currentUser = $userToUpdate;
        }
        $body = $res->getBody();
        $body->write(json_encode(['currentUser' => $currentUser, "data" => $userToUpdate]));
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
    if($currentUser->can('user.delete')) {
      $userToDelete = new User($args['id']);
      if($userToDelete && $currentUser->id === $userToDelete->id) {
        return $res->withStatus(403);
      } else {
        $userToDelete->delete();
      }
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser]));
    return $res->withBody($body);
  });

});
