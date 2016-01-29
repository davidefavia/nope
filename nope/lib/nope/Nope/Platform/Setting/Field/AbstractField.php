<?php

namespace Nope\Platform\Setting\Field;

use Stringy\Stringy as S;

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
      return $v;
    }
  }

  protected function getAttributesList() {
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
    $ngModel = $ngRepeat.'[multipleIndex]';
    $this->properties->attributes['ng-model'] = $ngModel;
    $pushed = 'null';
    if(get_class($this)==='\Nope\Platform\Setting\Field\Group') {
      if($this->properties->attributes['multiple']) {
        $pushed = '{}';
      }
    } else {
      if($this->properties->type==='model') {
        $pushed = '{}';
        if($this->properties->attributes['multiple']) {
          $pushed = '[]';
        }
      } elseif($this->properties->type==='table') {
        if($this->properties->multiple) {
          $pushed = '[]';
        }
      } elseif($this->properties->type==='pair') {
        if($this->properties->multiple) {
          $pushed = '[]';
        }
      }
    }
    return '<div class="list-group" ng-if="'.$ngRepeat.'.length">
      <div class="list-group-item clearfix" ng-repeat="item in '.$ngRepeat.' track by $index" ng-init="multipleIndex=$index;">
        <div class="row">
          <div class="col col-md-10">
            ' . $this->drawSingle() . '
          </div>
          <div class="col col-md-2">
            <div class="btn-group btn-group-xs toolbar pull-right">
              <a href="" class="btn" ng-click="'.$ngRepeat.'.swapItems(multipleIndex, multipleIndex-1);" ng-if="!$first"><i class="fa fa-arrow-up"></i></a>
              <a href="" class="btn" ng-click="'.$ngRepeat.'.swapItems(multipleIndex, multipleIndex+1);" ng-if="!$last"><i class="fa fa-arrow-down"></i></a>
              <a href="" class="btn text-danger" ng-click="'.$ngRepeat.'.removeItemAt(multipleIndex);"><i class="fa fa-times-circle"></i></a>
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
