<?php

use RedBeanPHP\R as R;

class Nope {

  static function isAlredyInstalled() {
    if(R::testConnection()) {
      $setting = Setting::getByKey('installation');
      return !is_null($setting->value);
    } else {
      return false;
    }
  }

}
