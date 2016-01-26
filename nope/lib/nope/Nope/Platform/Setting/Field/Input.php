<?php

namespace Nope\Platform\Setting\Field;

class Input extends AbstractField {

  function drawSingle() {
    $this->properties->attributes['class'] .= ' form-control';
    return '<input type="text" '. $this->getAttributesList() .' />';
  }

}
