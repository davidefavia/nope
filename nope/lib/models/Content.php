<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Content extends \Nope\Model {

  const MODELTYPE = 'content';

  function validate() {
    $contentValidator = v::attribute('title', v::alnum()->noWhitespace()->length(1,255));
    try {
      $contentValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

  function jsonSerialize() {
    return $this->model->export();
  }

}
