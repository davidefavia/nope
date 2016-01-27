<?php

namespace Nope\Platform\Setting\Field;

class Checkbox extends AbstractField {

  function drawSingle() {
    return '<label class="control-label">
      <input type="checkbox" '. $this->getAttributesList() .' />
      '.$this->properties->label.'
    </label>';
  }

}
