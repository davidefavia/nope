<?php

namespace Nope\Platform\Setting\Field;

class Text extends AbstractField {

  function draw() {
    return '<textarea '. $this->getAttributesList() .' ></textarea>';
  }

}
