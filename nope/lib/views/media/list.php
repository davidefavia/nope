<div class="row">
  <div id="model" class="col col-md-9 col-sm-8">
    <div ng-show="contentsList.length">
      <div class="form-group" nope-can="{{contentType}}.read">
        <input type="text" class="form-control" ng-model="q.title" placeholder="Filter {{contentType}} by title" />
      </div>
      <div class="list-group-item ng-cloak" ng-show="!filteredContentsList.length && q.title">No {{contentType}} found with filter "{{q.title}}".</div>
      <div class="row row-span clearfix">
        <div class="col col-md-3" ng-repeat="p in filteredContentsList = (contentsList | filter : q)" ng-show="filteredContentsList.length">
          <div class="list-group-item clearfix" style="{{'background-image:url('+p.preview+')'}}" ng-class="{active:p.id===selectedContent.id}">
            <a ng-href="#/content/{{contentType}}/view/{{p.id}}"><h4 class="list-group-item-heading">{{::p.title}}</h4></a>
          </div>
        </div>
      </div>
    </div>
    <a href="" ngf-select="uploadFiles($files);" multiple class="btn btn-sm btn-block btn-default" nope-can="media.create">Upload new {{contentType}} <i class="fa fa-plus"></i></a>
  </div>
  <div class="col col-md-3 col-sm-4" ui-view="content">
    <no-empty icon="file-text-o">Select {{contentType}}</no-empty>
  </div>
</div>
