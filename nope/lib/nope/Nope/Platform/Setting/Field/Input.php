<?php

namespace Nope\Platform\Setting\Field;

class Input extends AbstractField {

  function drawSingle() {
    return '<input type="text" '. $this->getAttributesList() .' />';
  }

}
