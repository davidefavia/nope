<?php

namespace Nope\Platform\Setting\Field;

class Model extends AbstractField {

  function draw() {
    return '<nope-model '. $this->getAttributesList() .'></nope-model>';
  }

  function getAttributesList() {
    $attrs = [];
    foreach($this->properties->attributes as $key => $value) {
      $attrs[] = $key . '="'.$value.'"';
    }
    return implode(' ', $attrs);
  }

}
