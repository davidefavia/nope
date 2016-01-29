<?php

namespace Nope;

class DateTime extends \Carbon\Carbon implements \JsonSerializable {

  function jsonSerialize() {
    return $this->__toString();
  }

}
