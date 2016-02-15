<?php

namespace Nope;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Menu extends Content {

  const MODELTYPE = 'menu';

  function jsonSerialize() {
    $json = parent::jsonSerialize();
    if(is_null($json->items)) {
      $json->items = [];
    } else {
      $json->items = json_decode($json->items);
    }
    return $json;
  }

  function setItems($items) {
    $this->items = json_encode(is_null($items)?[]:$items);
  }

}
