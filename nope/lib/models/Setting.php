<?php

namespace Nope;

use RedBeanPHP\R as R;

class Setting extends \Nope\Model {

  const MODELTYPE = 'setting';

  function validate() {
    return true;
  }

  function jsonSerialize() {
    return parent::jsonSerialize();
  }

  static function getByKey($key) {
    $setting = R::findOne(self::__getModelType(), 'key = ?', [$key]);
    return self::__to($setting);
  }

}
