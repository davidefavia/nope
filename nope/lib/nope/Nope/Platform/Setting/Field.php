<?php

namespace Nope\Platform\Setting;

use \Stringy\StaticStringy as S;

class Field {

  public $id;
  public $properties = [];
  public $instance;

  function __construct($id, $properties = []) {
    $this->id = $id;
    $this->properties = (object) $properties;
    if(!$this->properties->type) {
      $type = 'input';
    } else {
      $type = $this->properties->type;
    }
    $className = 'Nope\Platform\Setting\Field\\' . S::upperCaseFirst($type);
    $this->instance = new $className($this->id, $this->properties);
  }

  function isGroup() {
    return false;
  }

  function draw($ngModel = null) {
    $this->instance->setNgModel($ngModel);
    return '<div id="setting-'.$this->id.'">' . $this->instance->draw() . '</div>';
  }

  function toValue($v) {
    return $this->instance->toValue($v);
  }

  function fromValue($v) {
    return $this->instance->fromValue($v);
  }

}
