<?php

namespace Nope\Platform\Setting\Field;

class Text extends AbstractField {

  function drawSingle() {
    $this->properties->attributes['class'] .= ' form-control';
    return '<textarea '. $this->getAttributesList() .' ></textarea>';
  }

}
