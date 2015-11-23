<?php

namespace Nope;

$app->group(NOPE_ADMIN_ROUTE . '/user', function() {

  $this->post('/login', function ($req, $res) {
    $body = $req->getParsedBody();
    if($body['username'] && $body['password']) {
      if(User::authenticate($body['username'], $body['password'])) {
        return $res;
      } else {
        // user with credentials --> not found!
        return $res->withStatus(404);
      }
    } else {
      // bad request
      return $res->withStatus(400);
    }
  });

  $this->get('/loginstatus', function($req, $res) {
    $currentUser = \User::getAuthenticated();
    if(is_null($currentUser)) {
      return $res->withStatus(401);
    }
    $body = $res->getBody();
    $body->write(json_encode(['currentUser' => $currentUser]));
    return $res->withBody($body);
  });

  $this->get('/logout', function ($req, $res) {
    if(User::logout()) {
      return $res->withStatus(401);
    }
    return $res->withStatus(500);
  });

});
