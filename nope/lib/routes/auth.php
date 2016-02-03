<?php

namespace Nope;

use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE . '/user', function() {

  $this->post('/login', function ($request, $response) {
    $body = $request->getParsedBody();
    if($body['username'] && $body['password']) {
      if(User::login($body['username'], $body['password'])) {
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
    return $response->withJson(['currentUser' => $currentUser]);
  });

  $this->get('/logout', function ($request, $response) {
    if(User::logout()) {
      return $response->withStatus(200);
    }
    return $response->withStatus(500);
  });

  $this->post('/recovery', function ($request, $response) {
    $body = $request->getParsedBody();
    if(v::regex(Utils::EMAIL_REGEX_PATTERN)->validate($body['email'])) {
      $userByEmail = User::findByEmail($body['email']);
      if((int) $userByEmail->enabled) {
        // Send email!
        try {
          $code = Utils::generateSalt(time().$userByEmail->username, $userByEmail->salt);
          $userByEmail->resetCode = $code;
          $userByEmail->save();
          $toName = $userByEmail->pretty_name?:$userByEmail->username;
          $this->mailer->addAddress($userByEmail->email, $toName);
          $this->mailer->isHTML(false);
          $this->mailer->Subject = 'Nope: forgotten password';
          $this->mailer->Body = "Copy link into your browser to reset password:\n\n".Utils::getFullRequestUri($request, NOPE_ADMIN_ROUTE).'/#/login/'.$code;
          if(!$this->mailer->send()) {
            return $response->withStatus(500, $this->mailer->ErrorInfo);
          }
        } catch (phpmailerException $e) {
          throw $e; //Pretty error messages from PHPMailer
        } catch (Exception $e) {
          throw $e; //Boring error messages from anything else!
        }
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

  $this->post('/reset', function ($request, $response) {
    $body = $request->getParsedBody();
    if($body['password'] && v::identical($body['password'])->validate($body['confirm'])) {
      $userByResetCode = User::findByResetCode($body['code']);
      if($userByResetCode && (int) $userByResetCode->enabled) {
        $userByResetCode->setPassword($body['password']);
        User::authenticate($userByResetCode);
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

});
