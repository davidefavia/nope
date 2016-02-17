<nope-modal class="modal-datetime">
  <nope-modal-body>
    <div class="panel panel-default panel-datetime">
      <div class="panel-heading clearfix">
        <a href="" class="btn btn-default btn-xs pull-left" ng-click="previousMonth()"><i class="fa fa-arrow-left"></i></a>
        {{actualMonth}}
        <a href="" class="btn btn-default btn-xs pull-right" ng-click="nextMonth()"><i class="fa fa-arrow-right"></i></a>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr><th ng-repeat="day in days">{{day}}</th></tr>
          </thead>
          <tbody>
            <tr ng-repeat="row in matrix track by $index">
              <td ng-repeat="col in row track by $index" ng-class="{'empty-dell':!col,today:col.isToday}">
                <a href="" class="btn btn-block" ng-class="{'btn-info':(col.isToday && !col.isSelected), 'btn-success':col.isSelected}" ng-click="$parent.$parent.$parent.selectDay(todayYear, todayMonth, col.label);">{{col.label}}</a>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="form-inline">
          <div class="form-group">
            {{$parent.$parent.$parent.selectedDate | date: 'longDate'}}
            <input type="text" ng-model="$parent.$parent.$parent.selectedHours" class="form-control input-sm" size="2" placeholder="hh" />
            <input type="text" ng-model="$parent.$parent.$parent.selectedMinutes" class="form-control input-sm" size="2" placeholder="mm" />
            <input type="text" ng-model="$parent.$parent.$parent.selectedSeconds" class="form-control input-sm" size="2" placeholder="ss" />
          </div>
        </div>
      </div>
    </div>
  </nope-modal-body>
  <nope-modal-footer>
    <a href="" class="btn btn-default" nope-modal-close>Close</a>
    <a href="" class="btn btn-info" ng-click="$parent.$parent.$parent.selectNow()">Now</a>
    <a href="" class="btn btn-info" ng-click="$parent.$parent.$parent.selectToday()">Today</a>
    <a href="" class="btn btn-success" ng-disabled="!$parent.$parent.$parent.selectedDate" ng-click="$parent.$parent.$parent.select()">Select</a>
  </nope-modal-footer>
</nope-modal>
