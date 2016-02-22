<?php

namespace Nope\Platform\Setting\Field;

class Radio extends Select {

  function drawSingle() {
    if(count($this->properties->items)) {
      foreach($this->properties->items as $item) {
        $value = $item;
        $label = $item;
        if(is_array($item)) {
          $value = $item['value'];
          $label = $item['label'];
        }
        $template[] = '<div class="radio"><label><input type="radio" value="'.$value.'" '. $this->getAttributesList() .'>'.$label.'</div>';
      }
    }
    return implode('', $template);
  }

}
