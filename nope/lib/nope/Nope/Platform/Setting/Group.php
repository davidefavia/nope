<?php

namespace Nope\Platform\Setting;

class Group {

  public $key;
  public $properties = [];
  private $fields = [];

  function __construct($key, $properties = []) {
    $this->key = $key;
    $this->properties = (object) $properties;
  }

  function addField(Field $field) {
    $field->properties->group = $this->key;
    $field->properties->multipleGroup = $this->properties->multiple;
    $this->fields[] = $field;
  }

  function addGroup(Group $group) {
    $this->fields[] = $group;
  }

  function getFields() {
    return $this->fields;
  }

}
