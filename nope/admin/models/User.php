<?php

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class User extends Nope\Model {

  protected $type = 'user';

  function validate() {
    $userValidator = v::attribute('username', v::stringType()->length(1,20))
      ->attribute('password', v::stringType()->length(1,20));
    try {
      // @TODO better validation, obviously, do not create COPY!
      $copy = new stdClass();
      $copy->username = $this->model->username;
      $copy->password = $this->model->password;
      $userValidator->check($copy);
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

}
