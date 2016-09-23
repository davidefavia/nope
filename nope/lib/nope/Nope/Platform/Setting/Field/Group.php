<?php

namespace Nope\Platform\Setting\Field;

class Group extends AbstractField {

  protected $fields = [];

  function __construct($id, $properties) {
    $this->id = $id;
    $this->properties = $properties;
  }

  function setFields($fields) {
    $this->fields = $fields;
  }

  function drawSingle() {
    $ngModel = $this->properties->attributes['ng-model'];
    $html[] = '<div class="panel panel-default">
      <div class="panel-heading">
        ' . $this->properties->label . '
      </div>
      <div class="panel-body">';
    if($this->properties->description) {
      $html[] = '<p class="control-description">' . $this->properties->description . '</p>';
    }
    foreach($this->fields as $field) {
      $html[] = '<div class="form-group">';
      if($field->properties->label && $field->properties->type!=='checkbox') {
        $html[] = '<label class="control-label">' . $field->properties->label . '</label>';
      }
      if($field->properties->description) {
        $html[] = '<p class="control-description">' . $field->properties->description . '</p>';
      }
      $html[] = $field->draw($ngModel . '.');
      $html[] = '</div>';
    }
    $html[] = '</div></div>';
    return implode('', $html);
  }

  function toValue($v) {
    $p = [];
    if($this->properties->multiple) {
      if(is_array($v)) {
        foreach ($v as $i => $valueTo) {
          foreach($this->fields as $field) {
            $p[$i][$field->id] = $field->toValue($valueTo[$field->id]);
          }
        }
      } else {
        $i = 0;
        $p[$i] = [];
        foreach($this->fields as $field) {
          $p[$i][$field->id] = $field->toValue($v[$i][$field->id]);
        }
      }
    } else {
      foreach($this->fields as $field) {
        $p[$field->id] = $field->toValue($v[$field->id]);
      }
    }
    return $p;
  }

  function fromValue($v) {
    $p = [];
    if($this->properties->multiple) {
      if(is_array($v)) {
        foreach ($v as $i => $valueFrom) {
          foreach($this->fields as $field) {
            $p[$i][$field->id] = $field->fromValue($valueFrom->{$field->id});
          }
        }
      } else {
        $i = 0;
        $p[$i] = [];
        foreach($this->fields as $field) {
          $p[$i][$field->id] = $field->fromValue($v->{$field->id});
        }
      }
    } else {
      foreach($this->fields as $field) {
        $p[$field->id] = $field->fromValue($v->{$field->id});
      }
    }
    return $p;
  }

}
