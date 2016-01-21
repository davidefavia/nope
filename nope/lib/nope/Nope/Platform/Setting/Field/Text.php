<?php

namespace Nope\Platform\Setting\Field;

class Text extends AbstractField {

  function drawSingle() {
    return '<textarea '. $this->getAttributesList() .' ></textarea>';
  }

}
