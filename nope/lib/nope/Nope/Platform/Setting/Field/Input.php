<?php

namespace Nope\Platform\Setting\Field;

class Input extends AbstractField {

  function draw() {
    return '<input type="text" '. $this->getAttributesList() .' />';
  }

}
