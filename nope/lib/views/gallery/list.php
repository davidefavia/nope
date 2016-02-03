<div id="gallery" class="row">
  <div class="list-column col" ng-class="{'col-md-4 col-sm-6':!nope.isIframe}">
    <div class="searchbar">
      <form name="searchForm" ng-submit="search(q);">
        <div class="input-group" nope-can="{{contentType}}.read">
          <input type="text" class="form-control" ng-model="q.query" placeholder="Search" />
          <div class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>
      <a ng-if="!nope.isIframe" href="#/gallery/create" class="btn btn-sm btn-block btn-default" nope-can="gallery.create" ng-click="selectedGallery=null;">Create new gallery <i class="fa fa-plus"></i></a>
    </div>
    <div class="list-group">
      <div class="list-group-item ng-cloak" ng-show="!contentsList.length && q.query">No gallery found with filter "{{q.query}}".</div>
      <div class="list-group-item ng-cloak" ng-show="!metadata.count">No gallery found.</div>
      <div class="list-group-item clearfix media" ng-class="{active:p.id===selectedGallery.id}" ng-repeat="p in contentsList" ng-show="contentsList.length">
        <div class="media-left" ng-if="p.cover">
          <img class="media-object img-circle" ng-src="{{p.cover.preview.icon}}" />
        </div>
        <div class="media-body">
          <a ng-href="#/gallery/view/{{p.id}}" ng-model="selection" nope-content-selection="p"><h4 class="list-group-item-heading">{{p.title}}</h4></a>
          <p class="list-group-item-text">Media: {{p.media.length}}</p>
          <p class="list-group-item-text">Modified {{p.lastModificationDate | nopeMoment}}</p>
          <div ng-if="nope.isIframe" class="pull-right">
            <i class="fa fa-check-circle-o fa-2x" ng-show="!selection.hasItem(p);"></i>
            <i class="fa fa-check-circle fa-2x" ng-show="selection.hasItem(p);"></i>
          </div>
          <div ng-if="!nope.isIframe" class="btn-group btn-group-xs pull-right toolbar">
            <a ng-href="#/{{contentType}}/view/{{p.id}}" class="btn"><i class="fa fa-pencil"></i></a>
            <a ng-click="p.starred=!p.starred;save(p,$index);" class="btn star"><i class="fa" ng-class="{'fa-star-o':!p.starred,'fa-star':p.starred}"></i></a>
            <a href="" nope-content-delete="deleteContentOnClick(p);" ng-model="p" class="btn text-danger"><i class="fa fa-trash"></i></a>
          </div>
        </div>
      </div>
    </div>
    <a href="" class="btn btn-sm btn-block btn-default" ng-click="search(q,metadata.next)" ng-if="metadata.next>metadata.actual">More</a>
  </div>
  <div ng-if="!nope.isIframe" class="col col-md-8 col-sm-6" ui-view="content">
    <no-empty icon="object-group">
      <span ng-if="contentsList.length">Select {{contentType}}</span>
      <a href="#/gallery/create" ng-if="!contentsList.length" class="btn btn-sm btn-block btn-default" nope-can="gallery.create" ng-click="selectedGallery=null;">Create new {{contentType}} <i class="fa fa-plus"></i></a>
    </no-empty>
  </div>
</div>
