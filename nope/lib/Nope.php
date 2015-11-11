<?php

class Nope {

  static function isAlredyInstalled() {
    $setting = Setting::getByKey('installation');
    return !is_null($setting->key);
  }

}
