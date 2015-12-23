<div id="gallery" class="row">
  <div id="model" class="col col-md-3 col-sm-4" ng-if="contentsList.length">
    <div ng-show="contentsList.length">
      <div class="form-group" nope-can="{{contentType}}.read">
        <input type="text" class="form-control" ng-model="q.title" placeholder="Filter {{contentType}} by title" />
      </div>
      <div class="list-group">
        <div class="list-group-item ng-cloak" ng-show="!filteredContentsList.length && q.title">No {{contentType}} found with filter "{{q.title}}".</div>
        <div class="list-group-item clearfix" ng-class="{active:p.id===selectedGallery.id}" ng-repeat="p in filteredContentsList = (contentsList | filter : q)" ng-show="filteredContentsList.length">
          <a ng-href="#/{{contentType}}/view/{{p.id}}"><h4 class="list-group-item-heading">{{p.title}}</h4></a>
          <p class="list-group-item-text">Media: {{p.media.length}}</p>
          <p class="list-group-item-text">Modified on: {{p.last_modification_date | nopeDate:medium}}</p>
          <div class="btn-group btn-group-xs pull-right toolbar">
            <a ng-href="#/{{contentType}}/view/{{p.id}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
            <a href="" ng-click="deleteContent(p);" class="btn btn-default" nope-can="page.delete"><i class="fa fa-trash text-danger"></i></a>
          </div>
        </div>
      </div>
    </div>
    <a href="#/gallery/create" class="btn btn-sm btn-block btn-default" nope-can="gallery.create" ng-click="selectedGallery=null;">Create new {{contentType}} <i class="fa fa-plus"></i></a>
  </div>
  <div class="col" ng-class="{'col-md-9 col-sm-8':contentsList.length}" ui-view="content">
    <no-empty icon="object-group">
      <span ng-if="contentsList.length">Select {{contentType}}</span>
      <a href="#/gallery/create" ng-if="!contentsList.length" class="btn btn-sm btn-block btn-default" nope-can="gallery.create" ng-click="selectedGallery=null;">Create new {{contentType}} <i class="fa fa-plus"></i></a>
    </no-empty>
  </div>
</div>
