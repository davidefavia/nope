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
    switch($this->properties->type) {
      default:
      case 'input':
        $type = 'input';
        break;
      case 'text':
      case 'model':
      case 'pair':
      case 'table':
        $type = $this->properties->type;
        break;
    }
    $className = 'Nope\Platform\Setting\Field\\' . S::upperCaseFirst($type);
    $this->instance = new $className($this->id, $this->properties);
  }

  function isGroup() {
    return false;
  }

  function draw($ngModel = null) {
    $this->instance->setNgModel($ngModel);
    return $this->instance->draw();
  }

  function toValue($v) {
    return $this->instance->toValue($v);
  }

  function fromValue($v) {
    return $this->instance->fromValue($v);
  }

}
