<?php

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

  $this->get('/logout', function ($req, $res) {
    User::logout();
    return $res;
  })->add(auth());

});
