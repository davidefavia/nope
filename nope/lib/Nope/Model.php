<?php

namespace Nope;

use RedBeanPHP\R as R;

abstract class Model implements \JsonSerializable {

  const MODELTYPE = '';
  protected $model;

  public function __construct($id = null, $model = null) {
    $c = get_called_class();
    if(is_null($id) && is_null($model)) {
      $this->model = R::dispense($c::MODELTYPE);
    } else {
      if(is_null($model)) {
        $this->model = R::load($c::MODELTYPE, $id);
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
      return (object) $this->model->export();
  }

  public function save() {
    if($this->validate()) {
      $this->beforeSave();
      $this->model->id = R::store($this->model);
    }
  }

  public function import($body, $fields) {
    if(is_array($fields)) {
      foreach($fields as $f) {
        if(array_key_exists($f, $body)) {
          $this->model->$f = $body[$f];
        }
      }
    }
  }

  function beforeSave() {
    if(!$this->model->id) {
      $this->model->creationDate = new \DateTime();
    }
    $this->model->lastModificationDate = new \DateTime();
  }

  public function delete() {
    R::trash($this->model);
  }

}
