<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Page extends Content {

  const MODELTYPE = 'page';

  function jsonSerialize() {
    $obj = parent::jsonSerialize();
    return $obj;
  }

  function validate() {
    $contentValidator = v::attribute('title', v::alnum()->noWhitespace()->length(1,255));
    try {
      $contentValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

  static public function findById($id) {
    return self::__to(R::findOne(self::MODELTYPE, 'id = ?', [$id]));
  }

  static public function findAll($filters=null, $limit=-1, $offset=0, &$count=0, $orderBy='id asc') {
    $filters = (object) $filters;
    $params = [];
    /*if($filters->role) {
      $sql[] = 'role = ?';
      $params[] = $filters->role;
    }*/
    if($orderBy) {
      $sql[] = 'order by '.$orderBy;
    }
    $users = R::findAll(self::MODELTYPE, implode(' ',$sql),$params);
    return self::__to($users, $limit, $offset, $count);
  }



}
