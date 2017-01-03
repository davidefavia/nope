<nope-modal class="modal--upload" title="Upload media">
  <nope-modal-body>
    <a href="" nope-upload="onDone();" accept="{{$parent.accept}}" on-progress="progressList" class="btn btn-block btn-default">Upload <i class="fa fa-upload"></i></a>
    <ul class="list-group" ng-if="progressList">
      <li class="list-group-item" ng-class="{'list-group-item-success':(value.percentage===100),'list-group-item-danger':value.error}" ng-repeat="(key, value) in progressList">
        <div class="row flex-items-xs-middle">
          <div class="col-xs-8">{{key}} {{value.errorMessage}}</div>
          <div class="col-xs">
            <progress class="progress" ng-class="{'progress-info':(value.percentage<100 && !value.error),'progress-success':(value.percentage===100), 'progress-danger':value.error}" value="{{value.percentage}}" max="100"></progress>
          </div>
        </div>
      </li>
    </ul>
    <hr />
    <nope-import on-done="onDone();"></nope-import>
  </nope-modal-body>
</nope-modal>
