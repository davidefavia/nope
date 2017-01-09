<nope-modal class="modal--upload" title="Upload media">
  <nope-modal-body>
    <a href="" nope-upload="onDone();" accept="{{$parent.accept}}" on-progress="progressList" status="uploadingStatus" class="btn btn-block btn-outline-secondary btn-upload" ng-show="!uploadingStatus"><i class="fa fa-upload"></i>Choose files to upload</a>
    <ul class="list-group" ng-if="progressList">
      <li class="list-group-item" ng-class="{'list-group-item-success':(value.percentage===100),'list-group-item-danger':value.error}" ng-repeat="(key, value) in progressList">
        <div class="row">
          <div class="col-8">{{key}} {{value.errorMessage}}</div>
          <div class="col-2">
            <progress class="progress" ng-class="{'progress-info':(value.percentage<100 && !value.error),'progress-success':(value.percentage===100), 'progress-danger':value.error}" value="{{value.percentage}}" max="100"></progress>
          </div>
        </div>
      </li>
    </ul>
    <div ng-show="!uploadingStatus">
      <hr />
      <nope-import on-done="onDoneImport();"></nope-import>
    </div>
  </nope-modal-body>
</nope-modal>
