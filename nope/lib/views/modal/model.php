<nope-modal class="contents" title="Choose item">
  <nope-modal-body>
    <div class="list-group">
      <a href="" class="list-group-item" ng-repeat="item in itemsList" ng-click="onSelect(item);"><img class="img-rounded preview" ng-src="{{item.preview[preview]}}" ng-if="hasPreview" />{{item.title}}</a>
    </div>
  </nope-modal-body>
</nope-modal>
