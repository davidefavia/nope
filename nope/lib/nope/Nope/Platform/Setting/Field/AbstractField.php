<?php

namespace Nope\Platform\Setting\Field;

abstract class AbstractField {

  protected $id;
  protected $properties;

  function __construct($id, $properties) {
    $this->id = $id;
    $this->properties = $properties;
  }

  function setNgModel($ngModel) {
    $this->properties->attributes['ng-model'] = $ngModel . $this->id;
  }

  abstract function drawSingle();

  function toValue($v) {
    if($this->properties->multiple) {
      if(is_null($v)) {
        return [];
      }
      return $v;
    } else {
      if(is_array($v)) {
        return $v[0];
      }
      return $v;
    }
  }

  function fromValue($v) {
    if($this->properties->multiple) {
      if(is_null($v)) {
        return [];
      }
      return $v;
    } else {
      if(is_array($v)) {
        return $v[0];
      }
      return $v;
    }
  }

  protected function getAttributesList() {
    $this->properties->attributes['class'] .= ' form-control';
    $attrs = [];
    foreach($this->properties->attributes as $key => $value) {
      if($value===true || $value===false) {
        $value = ($value?'true':'false');
      }
      $attrs[] = $key . '="'.$value.'"';
    }
    return implode(' ', $attrs);
  }

  function drawMultiple() {
    $ngRepeat = $this->properties->attributes['ng-model'];
    $ngModel = $ngRepeat.'[$index]';
    $this->properties->attributes['ng-model'] = $ngModel;
    $pushed = 'null';
    if($this->properties->type==='model') {
      $pushed = '{}';
      if($this->properties->attributes['multiple']) {
        $pushed = '[]';
      }
    }
    return '<div class="list-group" ng-if="'.$ngRepeat.'.length">
      <div class="list-group-item clearfix" ng-repeat="item in '.$ngRepeat.' track by $index">
        <div class="row">
          <div class="col col-md-10">
            ' . $this->drawSingle() . '
          </div>
          <div class="col col-md-2">
            <div class="btn-group btn-group-xs toolbar pull-right">
              <a href="" class="btn" ng-click="'.$ngRepeat.'.swapItems($index, $index-1);" ng-if="!$first"><i class="fa fa-arrow-up"></i></a>
              <a href="" class="btn" ng-click="'.$ngRepeat.'.swapItems($index, $index+1);" ng-if="!$last"><i class="fa fa-arrow-down"></i></a>
              <a href="" class="btn text-danger" ng-click="'.$ngRepeat.'.removeItemAt($index);"><i class="fa fa-times-circle"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div><a href="" class="btn btn-default btn-block btn-sm" ng-click="'.$ngRepeat.'.push('.$pushed.');">Add</a>';
  }

  function draw() {
    if($this->properties->multiple) {
      return $this->drawMultiple();
    } else {
      return $this->drawSingle();
    }
  }

}
