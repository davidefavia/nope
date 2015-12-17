<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/user', function() {

  $this->post('/login', function ($request, $response) {
    $body = $request->getParsedBody();
    if($body['username'] && $body['password']) {
      if(User::authenticate($body['username'], $body['password'])) {
        return $response;
      } else {
        // user with credentials --> not found!
        return $response->withStatus(404, 'User not found.');
      }
    } else {
      // bad request
      return $response->withStatus(400);
    }
  });

  $this->get('/loginstatus', function($request, $response) {
    $currentUser = User::getAuthenticated();
    if(is_null($currentUser)) {
      return $response->withStatus(401);
    }
    $body = $response->getBody();
    $body->write(json_encode(['currentUser' => $currentUser]));
    return $response->withBody($body);
  });

  $this->get('/logout', function ($request, $response) {
    if(User::logout()) {
      return $response->withStatus(200);
    }
    return $response->withStatus(500);
  });

  $this->post('/recovery', function ($request, $response) {
    $body = $request->getParsedBody();
    if($body['email'] && v::attribute('email', v::regex(Utils::EMAIL_REGEX_PATTERN))) {
      $userByEmail = User::findByEmail($body['email']);
      if((int) $userByEmail->enabled) {
        return $response;
      } else {
        // user with credentials --> not found!
        return $response->withStatus(404, 'Email not found.');
      }
    } else {
      // bad request
      return $response->withStatus(400);
    }
  });

});
