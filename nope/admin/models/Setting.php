<?php

use RedBeanPHP\R as R;

class Setting extends Nope\Model {

  protected $type = 'setting';

  function validate() {
    return true;
  }

  static function getByKey($key) {
    return self::__transform(R::findOne('setting', 'key = ?', [$key]));
  }

}
