<?php

namespace Nope\Platform\Setting;

class Group {

  public $id;
  public $properties = [];
  public $instance;
  private $fields = [];

  function __construct($id, $properties = []) {
    $this->id = $id;
    $this->properties = (object) $properties;
    $this->instance = new Field\Group($this->id, $this->properties);
  }

  function isGroup() {
    return true;
  }

  function addField(Field $field) {
    $field->properties->group = $this->id;
    $field->properties->multipleGroup = $this->properties->multiple;
    $this->fields[] = $field;
  }

  function addGroup(Group $group) {
    $this->fields[] = $group;
  }

  function draw($ngModel = null) {
    $this->instance->setFields($this->fields);
    $this->instance->setNgModel($ngModel);
    return $this->instance->draw();
  }

  function toValue($v) {
    $this->instance->setFields($this->fields);
    return $this->instance->toValue($v);
  }

  function fromValue($v) {
    $this->instance->setFields($this->fields);
    return $this->instance->fromValue($v);
  }

}
