<nope-modal id="modal-datetime" class="modal-datetime">
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
                <a href="" class="btn btn-block" ng-class="{'btn-info':(col.isToday && !col.isSelected), 'btn-success':col.isSelected}" ng-disabled="!col.isEnabled" ng-click="col.isEnabled?selectDay(todayYear, todayMonth, col.label):return;">{{col.label}}</a>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="form-inline">
          <div class="form-group">
            {{$parent.$parent.$parent.selectedDate | date: 'longDate'}}
            <input type="text" ng-model="selectedHours" class="form-control input-sm" size="2" placeholder="hh" /> :
            <input type="text" ng-model="selectedMinutes" class="form-control input-sm" size="2" placeholder="mm" /> :
            <input type="text" ng-model="selectedSeconds" class="form-control input-sm" size="2" placeholder="ss" />
          </div>
        </div>
        <div class="alert alert-date-limit alert-danger" ng-show="!canSelect">
          <p>The chosen date is out the available limits:</p>
          <ul>
            <li>minimum date: {{$parent.$parent.$parent.lowerStringLimit | date : 'yyyy-MM-dd HH:mm:ss'}},</li>
            <li>maximum date: {{$parent.$parent.$parent.upperStringLimit | date : 'yyyy-MM-dd HH:mm:ss'}}.</li>
          </ul>
        </div>
        <div class="alert alert-timezone alert-info">
          <?php

          $timezone = new \DateTimeZone(date_default_timezone_get()); // Get default system timezone to create a new DateTimeZone object
          $offset = $timezone->getOffset(new \Nope\DateTime())/3600; // Offset in seconds to UTC

          if($offset<10) {
            if($offset>=0) {
              $offset2 = '+0' . $offset;
            } else {
              $offset2 = '-0' . $offset;
            }
          }

          if(is_int($offset)) {
            $offset = $offset2 . '00';
          } else {
            $offset = $offset2 . '30';
          }


          ?>
          <strong>Dates are ALWAYS relative to server timezone</strong>.<br/>
          Server time: <strong><?php echo new \Nope\DateTime(); ?> (UTC<?php echo $offset; ?>)</strong>.<br/>
          Your time: <strong>{{now | date : 'yyyy-MM-dd HH:mm:ss'}} (UTC{{$parent.$parent.$parent.now | date : 'Z'}})</strong>.
        </div>
      </div>
    </div>
  </nope-modal-body>
  <nope-modal-footer>
    <a href="" class="btn btn-default" nope-modal-close>Close</a>
    <a href="" class="btn btn-info" ng-if="todayVisible" ng-click="selectNow();">Now</a>
    <a href="" class="btn btn-info" ng-if="todayVisible" ng-click="selectToday();">Today</a>
    <a href="" class="btn btn-success" ng-disabled="!selectedDate || !canSelect" ng-click="select()">Select</a>
  </nope-modal-footer>
</nope-modal>
