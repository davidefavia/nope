<?php

namespace Nope;

use RedBeanPHP\R as R;

abstract class Model {

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

  static public function __transform($models) {
    $className = static::class;
    if(is_array($models)) {
      $list = [];
      foreach($models as $model) {
        $item = new $className(null, $model);
        $list[] = $item;
      }
      return $list;
    } else {
      $item = new $className(null, $models);
      return $item;
    }
  }


  abstract public function validate();

  public function save() {
    if($this->validate()) {
      $this->beforeSave();
      $this->model->id = R::store($this->model);
    }
  }

  private function beforeSave() {
    if(!$this->model->id) {
      $this->model->creationDate = new \DateTime();
    }
    $this->model->lastModificationDate = new \DateTime();
  }

  public function delete() {
    R::trash($this->model);
  }

}
