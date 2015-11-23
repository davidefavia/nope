<?php

use RedBeanPHP\R as R;

class Nope {

  private static $instance ;
  private $data = [] ;
  private $config = [];

  static function isAlredyInstalled() {
    if(R::testConnection()) {
      $setting = \Nope\Setting::getByKey('installation');
      return !is_null($setting->value);
    } else {
      return false;
    }
  }

  /**
   * Get singleton instance
   */
  static function getInstance() {
    if( ! self::$instance ) {
      $className = __CLASS__ ;
      self::$instance = new $className ;
    }
    return self::$instance ;
  }

  /**
   * Get global data item
   *
   * @param  mixed $key global data array key
   * @return mixed global data array value

   * @return null
   *
   */
  static function get($key = null) {
    if(isset($key)) {
      return self::getInstance()->data[$key] ;
    } else {
      return self::getInstance()->data ;
    }
  }

  static function getConfig($key = null) {
    if(isset($key)) {
      return self::getInstance()->config[$key] ;
    } else {
      return self::getInstance()->config ;
    }
  }

  /**
   * Set global data item
   *
   * @param  string key string for global data storage
   * @param  mixed  value for global data storage
   * @return null
   *
   */
  static function set($key , $value) {
    self::getInstance()->data[$key] = $value ;
  }

  static function setConfig($key , $value) {
    self::getInstance()->config[$key] = $value ;
  }

  static function add($key , $value, $priority=null) {
    if(is_null($priority)) {
      self::getInstance()->data[$key][] = $value ;
    } else {
      self::getInstance()->data[$key][$priority] = $value ;
    }
  }

  static function addConfig($key, $value, $priority=null) {
    if(is_null($priority)) {
      self::getInstance()->config[$key][] = $value ;
    } else {
      self::getInstance()->config[$key][$priority] = $value ;
    }
  }

  static function addConfigWithException($configKey, $key, $item, $exceptionMessage = '') {
    if(is_array(self::getInstance()->config[$configKey])) {
      if(!in_array($key, array_keys(self::getInstance()->config[$configKey]))) {
        self::getInstance()->config[$configKey][$key] = $item;
      } else {
        throw new \Exception($exceptionMessage);
      }
    } else {
      self::getInstance()->config[$configKey] = [];
      self::getInstance()->config[$configKey][$key] = $item;
    }
  }

  static function registerModel($key, $item) {
    self::getInstance()->addConfigWithException('nope.models', $key, $item, 'Model "'.$key.'" already exists');
  }

  static function registerRoute($route) {
    self::getInstance()->addConfig('nope.routes', $route);
  }

  static function registerRole($key, $item) {
    self::getInstance()->addConfigWithException('nope.roles', $key, $item, 'Role "'.$key.'" already exists');
  }

  static function registerMenuItem($item, $priority) {
    self::getInstance()->addConfigWithException('nope.admin.menu', $priority, $item, 'Menu item size already exists at priority '.$priority);
    ksort(self::getInstance()->config['nope.admin.menu']);
  }

}
