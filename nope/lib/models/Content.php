<?php

namespace Nope;

use \RedBeanPHP\R as R;
use \Respect\Validation\Validator as v;
use \Respect\Validation\Exceptions\NestedValidationException;

class Content extends Model {

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
    $author = $this->getAuthor();
    unset($this->model->author_id);
    $this->author = $author;
    return $this->model->export();
  }

  function setAuthor($user) {
    if($user->id) {
      $this->model->author = R::load(User::MODELTYPE,$user->id);
    }
  }

  private function getAuthor() {
    if($this->model->author_id) {
      return User::findByid($this->model->author_id);
      return $this->model->fetchAs(User::MODELTYPE)->author;
    }
    return null;
  }

}
