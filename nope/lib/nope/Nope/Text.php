<?php

namespace Nope;

class Text extends String {
  public $format;

  function __construct($string, $format = null, $encoding = 'UTF-8') {
    $this->format = $format;
    parent::__construct($string, $encoding);
  }

  function __toString() {
    $p = $this->toHTML();
    return $p->str;
  }

  function toHTML() {
    $textFormats = \Nope::getConfig('nope.content.format');
    if(count($textFormats)) {
      foreach ($textFormats as $key => $value) {
        if($value['key'] === $this->format) {
          if($value['parser']=== false) {
            return new self($this->str);
          } else {
            return new self($value['parser']->text($this->str));
          }
        }
      }
    }
    return new self($this->str);
  }

}
