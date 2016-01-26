<?php

namespace Nope\Platform\Setting\Field;

class Pair extends AbstractField {

  function drawSingle() {
    $ngModel = $this->properties->attributes['ng-model'];
    $this->properties->attributes['class'] .= ' table table-striped table-bordered';
    $this->properties->header = ['Key', 'Value'];
    if(count($this->properties->header)) {
      foreach ($this->properties->header as $value) {
        $header .= '<th>'.$value.'</th>';
      }
    }
    $maxRows = $this->properties->maxRows;
    return '<table '. $this->getAttributesList() .' >
      <thead>
        <tr>
          '.$header.'
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="row in '.$ngModel.' '.($maxRows?' | limitTo:' . $maxRows:'').' track by $index" ng-init="rowIndex=$index;">
          <td ng-repeat="col in [0,1] track by $index" ng-init="colIndex=$index;">
            <input type="text" class="form-control input-sm" ng-model="'.$ngModel.'[rowIndex][colIndex]" />
          </td>
          <td>
            <div class="btn-group btn-group-xs toolbar">
              <a href="" class="btn text-danger" ng-click="'.$ngModel.'.removeItemAt(rowIndex);"><i class="fa fa-times-circle"></i></a>
            </div>
          </td>
        </tr>
        <tr '.($maxRows?'ng-if="'.$ngModel.'.length<'.$maxRows.'"':'').'>
          <td colspan="3">
            <a href="" ng-click="'.$ngModel.'.addRow();" class="btn btn-default btn-block btn-sm">Add pair <i class="fa fa-arrow-down"></i></a>
          </td>
        </tr>
      </tbody>
    </table>';
  }

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
      if(!is_array($v[0])) {
        return [];
      }
      return $v;
    }
  }

}
