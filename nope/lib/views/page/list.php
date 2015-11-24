<div class="row">
  <div id="model" class="col col-md-3 col-sm-4">
    <div ng-show="contentsList.length">
      <div class="form-group" nope-can="{{contentType}}.read">
        <input type="text" class="form-control" ng-model="q.title" placeholder="Filter {{contentType}} by title" />
      </div>
      <div class="list-group">
        <div class="list-group-item ng-cloak" ng-show="!filteredContentsList.length && q.title">No {{contentType}} found with filter "{{q.title}}".</div>
        <div class="list-group-item clearfix" ng-repeat="p in filteredContentsList = (contentsList | filter : q)" ng-show="filteredContentsList.length">
          <h4 class="list-group-item-heading">{{p.title}}</h4>
          <div class="btn-group btn-group-xs pull-right toolbar">
            <a ng-href="#/content/{{contentType}}/edit/{{p.id}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
          </div>
        </div>
      </div>
    </div>
    <a href="#/content/page/create" class="btn btn-sm btn-block btn-default" nope-can="user.create">Create new {{contentType}} <i class="fa fa-plus"></i></a>
  </div>
  <div class="col col-md-9 col-sm-8" ui-view="content">
    <no-empty icon="file-text-o">Select {{contentType}}</no-empty>
  </div>
</div>
