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

  function toJson($v) {
    if(count($this->fields)) {
      $p = [];
      foreach($this->fields as $field) {
        $p[$field->id] = $field->toValue($v[$field->id]);
      }
      return json_encode($p);
    }
    return null;
  }

  function fromJson($v) {
    if(count($this->fields)) {
      $v = json_decode($v);
      $p = [];
      foreach($this->fields as $field) {
        $p[$field->id] = $field->fromValue($v->{$field->id});
      }
      return $p;
    }
    return null;
  }

  function getAttributesList() {
    $attrs = [];
    if(is_array($this->properties->attributes)) {
      foreach($this->properties->attributes as $key => $value) {
        if($value===true || $value===false) {
          $value = ($value?'true':'false');
        }
        $attrs[] = $key . '="'.$value.'"';
      }
      return implode(' ', $attrs);
    }
  }


}
