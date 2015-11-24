<?php

/**
 * Main Nope class. It is used mainly as a way to store configurations and transport global data.
 *
 * @author Davide Favia <davide.favia@gmail.com>
 * @package Nope
 */

use RedBeanPHP\R as R;

class Nope {

  /**
   * @access private
   * @var object $instance Nope singleton instance.
   */
  private static $instance ;
  /**
   * @access private
   * @var array $data Global data transportation array.
   */
  private $data = [] ;
  /**
   * @access private
   * @var array $config Global configurations transportation array.
   */
  private $config = [];

  /**
   * @uses RedBeanPHP\R::testConnection to test database connection.
   * @uses Nope\Settings::getByKey to retrieve installation flag inside settings table.

   * @return boolean Whether Nope is already installed or not.
   */
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
   *
   * @return object Nope singleton instance.
   */
  static function getInstance() {
    if( ! self::$instance ) {
      $className = __CLASS__ ;
      self::$instance = new $className ;
    }
    return self::$instance ;
  }

  /**
   * Get global data items.
   *
   * @param  mixed $key Global data array key.
   * @return mixed Global data array value or the whole data array if key is not setted.
   */
  static function get($key = null) {
    if(isset($key)) {
      return self::getInstance()->data[$key] ;
    } else {
      return self::getInstance()->data ;
    }
  }

  /**
   * Get global configuration items.
   *
   * @param  mixed $key Global configuration array key.
   * @return mixed Global configuration array value or the whole configuration array if key is not setted.
   */
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
   * @param  string Key string for global data storage.
   * @param  mixed Value for global data storage.
   * @return void
   */
  static function set($key , $value) {
    self::getInstance()->data[$key] = $value ;
  }

  /**
   * Set global configuration item
   *
   * @param  string Key string for global configuration storage.
   * @param  mixed Value for global configuration storage.
   * @return void
   */
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