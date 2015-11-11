<?php

namespace Nope;

use RedBeanPHP\R as R;

abstract class Model {

  protected $type;
  protected $model;

  public function __construct($id = null) {
    if(is_null($id)) {
      $this->model = R::dispense($this->type);
    } else {
      $this->model = R::load($this->type, $id);
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
        $item = new $className;
        $item->model = $model;
        $list[] = $item;
      }
      return $list;
    } else {
      $item = new $className;
      $item->model = $model;
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
