<?php

namespace Nope\Platform\Setting;

use \Stringy\StaticStringy as S;

class Field {

  public $id;
  public $properties = [];

  function __construct($id, $properties = []) {
    $this->id = $id;
    $this->properties = (object) $properties;
  }

  function draw($ngModel) {
    switch($this->properties->type) {
      default:
        $type = 'input';
        break;
      case 'text':
      case 'model':
        $type = $this->properties->type;
        break;
    }
    $className = 'Nope\Platform\Setting\Field\\' . S::upperCaseFirst($type);
    $instance = new $className($this->id, $this->properties, $ngModel);
    return $instance->draw();
  }

}
