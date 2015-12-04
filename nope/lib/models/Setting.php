<?php

namespace Nope;

use RedBeanPHP\R as R;

class Setting extends \Nope\Model {

  const MODELTYPE = 'setting';

  function validate() {
    return true;
  }

  function jsonSerialize() {
    return;
  }

  static function getByKey($key) {
    $setting = R::findOne(self::MODELTYPE, 'key = ?', [$key]);
    return self::__to($setting);
  }

}
