<?php

namespace Nope\Platform\Setting\Field;

abstract class AbstractField {

  protected $id;
  protected $properties;

  function __construct($id, $properties, $ngModel = 'setting.value.') {
    $this->id = $id;
    $this->properties = $properties;
    $this->properties->attributes['ng-model'] = $ngModel . $this->id;
  }

  abstract function draw();

  protected function getAttributesList() {
    $this->properties->attributes['class'] .= ' form-control';
    $attrs = [];
    foreach($this->properties->attributes as $key => $value) {
      $attrs[] = $key . '="'.$value.'"';
    }
    return implode(' ', $attrs);
  }

}
