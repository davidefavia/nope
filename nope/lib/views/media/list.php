<div class="row">
  <div id="model" class="col" ng-class="{'col-md-9 col-sm-8':selectedMedia}">
    <div class="row">
      <div class="col col-md-10" ng-show="contentsList.length">
        <div class="form-group" nope-can="{{contentType}}.read">
          <input type="text" class="form-control" ng-model="q.title" placeholder="Filter {{contentType}} by title" />
        </div>
      </div>
      <div class="col" ng-class="{'col-md-2':contentsList.length}">
        <a href="" nope-upload="onUploadDone()" class="btn btn-block btn-default" nope-can="{{contentType}}.create">Upload <i class="fa fa-plus"></i></a>
      </div>
    </div>
    <div class="list-group-item ng-cloak" ng-show="!filteredContentsList.length && q.title">No {{contentType}} found with filter "{{q.title}}".</div>
    <div class="row row-span clearfix">
      <div class="col col-md-3" ng-repeat="p in filteredContentsList = (contentsList | filter : q)" ng-show="filteredContentsList.length">
        <div class="list-group-item clearfix" style="{{'background-image:url('+p.preview+')'}}" ng-class="{active:p.id===selectedMedia.id}">
          <a ng-href="#/{{contentType}}/view/{{p.id}}"><h4 class="list-group-item-heading">{{p.title}}</h4></a>
        </div>
      </div>
    </div>
  </div>
  <div class="col col-md-3 col-sm-4" ui-view="content" ng-show="selectedMedia">
    <no-empty icon="file-text-o">Select {{contentType}}</no-empty>
  </div>
</div>
