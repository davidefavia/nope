<?php

use Respect\Validation\Validator as v;

class Content extends \Nope\Model {

  const MODELTYPE = 'content';

  function validate() {
    $contentValidator = v::attribute('body', v::notEmpty());
    return $contentValidator->validator($this->model);
  }

  function jsonSerialize() {
    return;
  }


}
