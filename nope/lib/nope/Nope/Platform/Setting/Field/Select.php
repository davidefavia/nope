<?php

namespace Nope\Platform\Setting\Field;

class Select extends AbstractField {

  function drawSingle() {
    $this->properties->attributes['class'] .= ' form-control';
    $template[] = '<select '. $this->getAttributesList() .'>';
    if(count($this->properties->items)) {
      foreach($this->properties->items as $item) {
        $value = $item;
        $label = $item;
        if(is_array($item)) {
          $value = $item['value'];
          $label = $item['label'];
        }
        $template[] = '<option value="'.$value.'">'.$label.'</option>';
      }
    }
    $template[] = '</select>';
    return implode('', $template);
  }

}
