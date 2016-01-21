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
    $json->value = $this->fromJsonValue($json->value);
    return $json;
  }

  function beforeSave() {
    $this->value = $this->toJsonValue();
    parent::beforeSave();
  }

  static function findByKey($key) {
    $setting = R::findOne(self::__getModelType(), 'settingkey = ?', [$key]);
    return self::__to($setting);
  }

  private function toJsonValue() {
    $settingsList = \Nope::getConfig('nope.settings');
    $json = [];
    foreach ($settingsList as $setting) {
      if($setting->settingkey === $this->settingkey) {
        $fields = $setting->getFields();
        if(count($fields)) {
          foreach ($fields as $field) {
            $theValue = $this->value[$field->id];
            $json[$field->id] = $field->toValue($theValue);
          }
        }
      }
    }
    return json_encode($json);
  }

  private function fromJsonValue($value) {
    $settingsList = \Nope::getConfig('nope.settings');
    $json = [];
    $value = json_decode($value) ;
    foreach ($settingsList as $setting) {
      if($setting->settingkey === $this->settingkey) {
        $fields = $setting->getFields();
        if(count($fields)) {
          foreach ($fields as $field) {
            $theValue = $value->{$field->id};
            $json[$field->id] = $field->fromValue($theValue);
          }
        }
      }
    }
    return $json;
  }

}
