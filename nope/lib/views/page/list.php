<div class="row">
  <div class="list-column col col-md-4 col-sm-6" ng-show="contentsList.length">
    <div class="searchbar">
      <div class="form-group" nope-can="{{contentType}}.read">
        <input type="text" class="form-control" ng-model="q.title" placeholder="Filter {{contentType}} by title" />
      </div>
      <a href="#/content/page/create" class="btn btn-sm btn-block btn-default" nope-can="user.create">Create new {{contentType}} <i class="fa fa-plus"></i></a>
    </div>
    <div class="list-group">
      <div class="list-group-item ng-cloak" ng-show="!filteredContentsList.length && q.title">No {{contentType}} found with filter "{{q.title}}".</div>
      <div class="list-group-item clearfix media" ng-class="{active:p.id===selectedContent.id}" ng-repeat="p in filteredContentsList = (contentsList | filter : q)" ng-show="filteredContentsList.length">
        <div class="media-left" ng-if="p.cover">
          <img class="media-object img-circle" ng-src="{{p.cover.preview.icon}}" alt="...">
        </div>
        <div class="media-body">
          <a ng-href="#/content/{{contentType}}/view/{{p.id}}"><h4 class="list-group-item-heading">{{::p.title}}</h4></a>
          <p class="list-group-item-text">Modified on: {{p.lastModificationDate | nopeDate:medium}}</p>
          <div class="btn-group btn-group-xs pull-right toolbar">
            <a ng-href="#/content/{{contentType}}/edit/{{p.id}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
            <a href="" ng-click="deleteContent(p);" class="btn btn-default" nope-can="page.delete"><i class="fa fa-trash"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="detail-column col" ng-class="{'col-md-8 col-sm-6':contentsList.length}" ui-view="content">
    <no-empty icon="file-text-o">
      <span ng-if="contentsList.length">Select {{contentType}}</span>
      <a href="#/content/page/create" class="btn btn-default" nope-can="user.create" ng-if="!contentsList.length">Create new {{contentType}} <i class="fa fa-plus"></i></a>
    </no-empty>
  </div>
</div>
