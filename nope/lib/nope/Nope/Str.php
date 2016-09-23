<?php

namespace Nope;

class Str extends \Stringy\Stringy implements \JsonSerializable {

  function __construct($string, $encoding = 'UTF-8') {
    parent::__construct($string, $encoding);
  }

  function jsonSerialize() {
    return $this->__toString();
  }

}
