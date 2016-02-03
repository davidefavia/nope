<?php

namespace Nope;

class Widget {

  public $name;
  public $attributes;
  public $data;

  function __construct($name, $attributes, $data = null) {
    $this->name = $name;
    $this->attributes = $attributes;
    $this->data = $data;
  }

  function getTemplate() {
    $template = $this->name;
    if($this->attributes->template) {
      $template .= '-' . $this->attributes->template;
    }
    return NOPE_APP_DIR.'widgets/'.$template.'.php';
  }

  function getHtml() {
    ob_start();
    try {
      $attributes = $this->attributes;
      if(!is_null($this->data)) {
        extract($this->data);
      }
      $template = $this->getTemplate();
      include($template);
      $buffer = ob_get_contents();
    } catch(Exception $e) {
      $buffer = $e->getMessage();
    }
    ob_end_clean();
    return $buffer;
  }

}
