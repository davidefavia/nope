<?php

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/user', function() {

  $this->get('', function($req, $res) {
    $currentUser = \User::getAuthenticated();
    $usersList = \User::findAll();
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $usersList]));
    return $res->withBody($body);
  });

  $this->post('', function($req, $res, $args) {
    $currentUser = \User::getAuthenticated();
    if($currentUser->can('profile.create')) {
      $fields = ['username','email','description','enabled','pretty_name','role'];
      $userToCreate = new User();
      $body = $req->getParsedBody();
      if(v::identical($body['password'])->validate($body['confirm'])) {
        $userToCreate->import($body, $fields);
        $userToCreate->setPassword($body['password']);
        $userToCreate->save();
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
    $currentUser = \User::getAuthenticated();
    if($currentUser->can('profile.read') || $currentUser->id === $args['id']) {
      $user = \User::findById($args['id']);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser, "data" => $user]));
    return $res->withBody($body);
  });

  $this->put('/{id}', function($req, $res, $args) {
    $currentUser = \User::getAuthenticated();
    if($currentUser->can('profile.update')) {
      if($currentUser->isAdmin()) {
        $fields = ['email','description','enabled','pretty_name','role'];
      } else {
        $fields = ['email','description','pretty_name'];
      }
      $userToUpdate = new User($args['id']);
      $userToUpdate->import($req->getParsedBody(), $fields);
      $userToUpdate->save();
      if($currentUser->id === $userToUpdate->id) {
        $userToUpdate->saveInSession();
        $currentUser = $userToUpdate;
      }
      if($userToUpdate) {
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

});
