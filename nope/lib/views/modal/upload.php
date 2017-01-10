<nope-modal class="modal--upload" title="Upload media">
  <nope-modal-body>
    <div ng-show="!progressList.status" ng-hide="progressImport.status">
      <a href="" nope-upload="onDone();" accept="{{$parent.accept}}" on-progress="progressList" class="btn btn-block btn-outline-secondary btn-upload" ng-show="!progressList.status"><i class="fa fa-upload"></i>Choose files to upload</a>
    </div>
    <ul class="list-group" ng-show="progressList.status && !progressImport.status">
      <li class="list-group-item" ng-class="{'list-group-item-success':(value.percentage===100),'list-group-item-danger':value.error}" ng-repeat="(key, value) in progressList.list">
        <div class="d-flex w-100 justify-content-between">
          <h6>{{key}}</h6>
          <small ng-if="value.percentage && value.percentage<100">{{value.percentage}}%</small>
          <i class="fa fa-check-circle" ng-if="value.percentage===100"></i>
          <i class="fa fa-exclamation-circle" ng-if="value.errorMessage"></i>
        </div>
        <p class="text-danger" ng-show="value.errorMessage"><small>{{value.errorMessage}}</small></p>
      </li>
    </ul>
    <div ng-show="!progressList.status">
      <hr ng-if="!progressImport.status" />
      <nope-import on-done="onDone();" on-progress="progressImport"></nope-import>
    </div>
  </nope-modal-body>
  <nope-modal-footer ng-show="showFooter">
    <a href="" class="btn btn-success btn-lg" ng-click="onDoneFooter()">Done</a>
  </nope-modal-footer>
</nope-modal>
