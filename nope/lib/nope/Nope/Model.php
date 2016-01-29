<?php

namespace Nope;

use RedBeanPHP\R as R;
use Stringy\StaticStringy as S;

abstract class Model implements \JsonSerializable {

  const MODELTYPE = '';
  protected $model;

  static function __getModelType() {
    $c = get_called_class();
    return $c::MODELTYPE;
  }

  public function __construct($id = null, $model = null) {

    if(is_null($id) && is_null($model)) {
      $this->model = R::dispense(self::__getModelType());
    } else {
      if(is_null($model)) {
        $this->model = R::load(self::__getModelType(), $id);
      } else {
        $this->model = $model;
      }
    }
  }

  public function __set($name, $value) {
    $this->model->$name = $value;
  }

  public function __get($name) {
    return $this->model->$name;
  }

  static function __to($data, $limit=-1, $offset=0, &$count=0) {
    $className = static::class;
    if(is_null($data)) {
      return null;
    } elseif(is_array($data)) {
      $count = count($data);
      if($limit>0) {
        $data = array_slice($data, $offset, $limit);
      } else {
        $data = array_slice($data, $offset);
      }
      $list = [];
      foreach($data as $d) {
        $item = new $className(null, $d);
        $list[] = $item;
      }
      return $list;
    }
    $item = new $className(null, $data);
    return $item;
  }

  abstract public function validate();

  public function jsonSerialize() {
    $json = (object) $this->model->export();
    $json->id = (int) $json->id;
    $json->custom = $this->getModelSettings($json->custom);
    $tmp = [];
    foreach ($json as $key => $value) {
      $tmp[(string) S::camelize($key)] = $value;
    }
    $tmp['creationDate'] = new DateTime($tmp['creationDate']);
    $tmp['lastModificationDate'] = new DateTime($tmp['lastModificationDate']);
    return (object) $tmp;
  }

  function toJson() {
    $c = get_called_class();
    return $c::jsonSerialize();
  }

  public function save() {
    if($this->validate()) {
      $this->beforeSave();
      $this->id = R::store($this->model);
    }
  }

  public function import($body, $fields) {
    if(is_array($fields)) {
      foreach($fields as $f) {
        if(array_key_exists($f, $body)) {
          $this->$f = $body[$f];
        }
      }
    }
  }

  function beforeSave() {
    if(!$this->id) {
      $this->creationDate = new \DateTime();
    }
    $this->lastModificationDate = new \DateTime();
  }

  function getModelSettings($custom) {
    $setting = \Nope::getCustom(self::__getModelType());
    if(!is_null($setting)) {
        return $setting->fromJson($custom);
    } else {
        return null;
    }
  }

  function setModelSettings($value) {
    $setting = \Nope::getCustom(self::__getModelType());
    if(!is_null($setting)) {
        $this->custom = $setting->toJson($value);
    } else {
        $this->custom = null;
    }
  }

  public function delete() {
    R::trash($this->model);
  }

  static public function findById($id) {
    return self::__to(R::findOne(self::__getModelType(), 'id = ?', [$id]));
  }

  static public function __getSql($filters, &$params=[], $p = null) {
    return [];
  }

  static public function findAll($filters=[], $limit=-1, $offset=0, &$count=0, $orderBy='id desc') {
    $params = [];
    $c = get_called_class();
    $sql = $c::__getSql($filters, $params);
    if($orderBy) {
      $sql[] = 'order by '.$orderBy;
    }
    $usersList = R::findAll(self::__getModelType(), implode(' ',$sql),$params);
    return self::__to($usersList, $limit, $offset, $count);
  }

}
