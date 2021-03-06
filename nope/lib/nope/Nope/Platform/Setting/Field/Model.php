<?php

namespace Nope\Platform\Setting\Field;

use \Stringy\StaticStringy as S;

class Model extends AbstractField {

  function drawSingle() {
    return '<nope-model '. $this->getAttributesList() .'></nope-model>';
  }

  function getAttributesList() {
    $attrs = [];
    $this->properties->attributes['model'] = $this->properties->model;
    foreach($this->properties->attributes as $key => $value) {
      if($value===true || $value===false) {
        $value = ($value?'true':'false');
      }
      if($key === 'href') {
        $p = $this->properties->attributes['ng-model'];
        if($this->properties->attributes['multiple']) {
          $value .= (S::contains($key, '?') ? '&':'?') . 'excluded={{('.$p.' | nopeGetIds).join(\',\')}}';
        }
      } elseif($key === 'model') {
        $value = S::slugify($value);
      }
      $attrs[] = $key . '="'.$value.'"';
    }
    return implode(' ', $attrs);
  }

  function toValue($v) {
    if($this->properties->multiple) {
      if(is_null($v)) {
        return [];
      }
      $p = [];
      if($this->properties->attributes['multiple']) {
        foreach ($v as $i => $key) {
          $p[$i] = [];
          foreach ($key as $j => $key2) {
            $p[$i][] = $key2['id'];
          }
        }
      } else {
        foreach ($v as $i => $key) {
          $p[] = $key['id'];
        }
      }
      return $p;
    } else {
      if($this->properties->attributes['multiple']) {
        if(is_null($v)) {
          return [];
        }
        $p = [];
        foreach ($v as $i => $key) {
          $p[] = $key['id'];
        }
        return $p;
      } else {
        return $v['id'];
      }
    }
  }

  function fromValue($v) {
    if($this->properties->multiple) {
      if(is_null($v)) {
        return [];
      }
      $p = [];
      if($this->properties->attributes['multiple']) {
        if(is_array($v)) {
          foreach ($v as $i => $key) {
            $p[$i] = [];
            foreach ($key as $j => $key2) {
              if($key2) {
                $model = new $this->properties->model($key2);
                $p[$i][] = $model->toJson();
              }
            }
          }
        }
      } else {
        if(is_array($v)) {
          foreach ($v as $key) {
            if($key) {
              $model = new $this->properties->model($key);
              $p[] = $model->toJson();
            }
          }
        }
      }
      return $p;
    } else {
      if($this->properties->attributes['multiple']) {
        $p = [];
        if(is_array($v)) {
          foreach ($v as $key) {
            if($key) {
              $model = new $this->properties->model($key);
              $p[] = $model->toJson();
            }
          }
        }
        return $p;
      } else {
        if($v) {
          $model = new $this->properties->model($v);
          if($model->id) {
            return $model->toJson();
          }
          return null;
        }
      }
    }
  }

}
