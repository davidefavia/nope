<?php

namespace Nope;

use RedBeanPHP\R as R;
use \Respect\Validation\Validator as v;

class Setting extends \Nope\Model {

  const MODELTYPE = 'setting';

  function validate() {
    $contentValidator = v::attribute('settingkey', v::length(1,255));
    try {
      $contentValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

  function jsonSerialize() {
    $json = parent::jsonSerialize();
    $json->value = json_decode($json->value);
    return $json;
  }

  function beforeSave() {
    $this->value = json_encode($this->value);
    parent::beforeSave();
  }

  static function findByKey($key) {
    $setting = R::findOne(self::__getModelType(), 'settingkey = ?', [$key]);
    return self::__to($setting);
  }

}
