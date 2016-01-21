<?php

namespace Nope\Platform;

class Setting {

  public $settingkey;
  public $properties = [];
  private $fields = [];

  function __construct($key, $properties = []) {
    $this->settingkey = $key;
    $this->properties = (object) $properties;
  }

  function addField(Setting\Field $field) {
    $this->fields[] = $field;
  }

  function addGroup(Setting\Group $fieldsGroup) {
    $this->fields[] = $fieldsGroup;
  }

  function getFields() {
    return $this->fields;
  }


}
