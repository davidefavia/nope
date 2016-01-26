<?php

namespace Nope\Platform\Setting\Field;

class Table extends AbstractField {

  function drawSingle() {
    $ngModel = $this->properties->attributes['ng-model'];
    $this->properties->attributes['class'] .= ' table table-striped table-bordered';
    $reOrderRows = ($this->properties->reorder===false?false:true);
    $maxColumns = $this->properties->maxColumns;
    if(count($this->properties->header)) {
      $fixedColumns = true;
      $maxColumns = count($this->properties->header);
      foreach ($this->properties->header as $value) {
        $header .= '<th>'.$value.'</th>';
      }
    } else {
      $header = '<th ng-repeat="col in '.$ngModel.'[0] track by $index">Column #{{$index+1}}</th>';
      if($this->properties->columns) {
        $fixedColumns = true;
        $maxColumns = $this->properties->columns;
      }
    }
    $maxRows = $this->properties->maxRows;
    return '<table '. $this->getAttributesList() .' >
      <thead>
        <tr>
          '.$header.'
          '.($reOrderRows?'<th></th>':'').'
        </tr>
      </thead>
      <tbody>
      '.($fixedColumns?'':'<tr>
          <td ng-repeat="col in '.$ngModel.'[0] track by $index" ng-init="colIndex=$index;">
            <div class="btn-group btn-group-xs toolbar">
              <a href="" class="btn" ng-click="'.$ngModel.'.swapCols(colIndex, colIndex-1);" ng-if="!$first"><i class="fa fa-arrow-left"></i></a>
              <a href="" class="btn" ng-click="'.$ngModel.'.swapCols(colIndex, colIndex+1);" ng-if="!$last"><i class="fa fa-arrow-right"></i></a>
              <a href="" class="btn text-danger" ng-click="'.$ngModel.'.removeCol(colIndex);"><i class="fa fa-times-circle"></i></a>
            </div>
          </td>
          '.($reOrderRows?'<td></td>':'').'
        </tr>').'
        <tr ng-repeat="row in '.$ngModel.' '.($maxRows?' | limitTo:' . $maxRows:'').' track by $index" ng-init="rowIndex=$index;">
          <td ng-repeat="col in row '.($fixedColumns?' | limitTo:' . $maxColumns:'').' track by $index" ng-init="colIndex=$index;">
            <input type="text" class="form-control input-sm" ng-model="'.$ngModel.'[rowIndex][colIndex]" />
          </td>
          '.($reOrderRows?'<td>
            <div class="btn-group btn-group-xs toolbar">
              <a href="" class="btn" ng-click="'.$ngModel.'.swapItems(rowIndex, rowIndex-1);" ng-if="!$first"><i class="fa fa-arrow-up"></i></a>
              <a href="" class="btn" ng-click="'.$ngModel.'.swapItems(rowIndex, rowIndex+1);" ng-if="!$last"><i class="fa fa-arrow-down"></i></a>
              <a href="" class="btn text-danger" ng-click="'.$ngModel.'.removeItemAt(rowIndex);"><i class="fa fa-times-circle"></i></a>
            </div>
          </td>':'').'
        </tr>
        <tr '.($maxRows?'ng-if="'.$ngModel.'.length<'.$maxRows.'"':'').'>
          <td colspan="{{'.$ngModel.'[0].length+1}}">
            <div class="row">
              <div '.($fixedColumns?'ng-class="{\'col-md-6\':'.$ngModel.'[0] && '.$ngModel.'[0].length<'.$maxColumns.', \'col-md-12\':'.$ngModel.'[0].length && '.$ngModel.'[0].length>='.$maxColumns.'}"':'ng-class="{\'col-md-6\':'.$ngModel.'[0], \'col-md-12\':!'.$ngModel.'[0].length}"').'>
                <a href="" ng-click="'.$ngModel.'.addRow();" class="btn btn-default btn-block btn-sm">Add row <i class="fa fa-arrow-down"></i></a>
              </div>
              '.($fixedColumns?'<div class="col-md-6" ng-if="'.$ngModel.'[0] && '.$ngModel.'[0].length<'.$maxColumns.'">
                <a href="" ng-click="'.$ngModel.'.addCol();" class="btn btn-default btn-block btn-sm">Add column <i class="fa fa-arrow-right"></i></a>
              </div>':'<div class="col-md-6" ng-if="'.$ngModel.'[0]">
                <a href="" ng-click="'.$ngModel.'.addCol();" class="btn btn-default btn-block btn-sm">Add column <i class="fa fa-arrow-right"></i></a>
              </div>').'
            </div>
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
      if(!is_array($v[0])) {
        return [$v];
      }
      return $v;
    }
  }

  function fromValue($v) {
    if($this->properties->multiple) {
      if(is_null($v)) {
        return [[]];
      }
      return $v;
    } else {
      if(!is_array($v[0])) {
        return [$v];
      }
      return $v;
    }
  }
}
