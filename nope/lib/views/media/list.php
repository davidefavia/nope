<div id="media" class="row tmpl-searchbar" ng-class="{'has-detail':selectedMedia}">
  <div class="backdrop"></div>
  <div class="col-xs list-column">
    <nav class="navbar navbar-fixed-top navbar-light bg-faded offset-md-3 offset-sm-3 offset-lg-2 searchbar" nope-can="media.read">
      <form name="searchForm" ng-submit="search(q);">
        <div class="row">
          <div class="col-xs-7">
            <input type="text" class="form-control" ng-model="q.query" placeholder="Search" />
          </div>
          <div class="col-xs" ng-hide="hideMimetypeOptions">
            <select class="form-control" ng-model="q.mimetype">
              <option value="">All</option>
              <option value="image/">Only images</option>
              <option value="!image/">Not images</option>
              <option value="provider">Only from providers</option>
            </select>
          </div>
          <div class="col-xs-1">
            <button type="submit" class="btn btn-secondary btn-info"><i class="fa fa-search"></i></button>
          </div>
          <div class="col-xs" nope-can="media.create">
            <a href="" nope-upload-modal="onUploadDone()" accept="{{acceptedFiles}}" class="btn btn-outline-success">Create new media <i class="fa fa-upload"></i></a>
          </div>
        </div>
      </form>
    </nav>
    <div class="list-group-item ng-cloak" ng-show="!contentsList.length && (q.query || q.mimetype)">No media found with filter "{{q}}".</div>
    <div ng-if="!contentsList.length">
      <nope-empty icon="upload">
        <a href="" nope-upload="onUploadDone()" class="btn btn-default" nope-can="media.create">Upload <i class="fa fa-plus"></i></a>
      </nope-empty>
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-3" ng-repeat="p in contentsList" ng-show="contentsList.length">
        <div class="card card--media" ng-class="{active:p.id===selectedMedia.id}">
          <div class="card-image-block">
            <nope-lazy src="p.preview.thumb"></nope-lazy>
            <div class="btn-group btn-group-sm toolbar" ng-if="!nope.isIframe">
              <a ng-click="p.starred=!p.starred;save(p,$index);" class="btn text-white"><i class="fa" ng-class="{'fa-star-o':!p.starred,'fa-star':p.starred}"></i></a>
              <a href="" class="btn text-white" ng-click="rotate(p,90,$index);" ng-if="p.isImage"><i class="fa fa-rotate-left"></i></a>
              <a href="" class="btn text-white" ng-click="rotate(p,-90,$index);" ng-if="p.isImage"><i class="fa fa-rotate-right"></i></a>
              <a href="" nope-zoom="p.url" class="btn text-white" ng-if="p.isImage"><i class="fa fa-arrows-alt"></i></a>
              <a href="" class="btn text-danger" nope-content-delete="deleteContentOnClick(p);" ng-model="p"><i class="fa fa-trash"></i></a>
            </div>
          </div>
          <div class="card-block">
            <a ng-href="#/media/view/{{p.id}}" nope-content-selection="p" ng-model="selection" class="btn-select card-link">
              <h4 class="card-title"><i class="fa {{'fa-'+(p.provider | lowercase)}}" ng-if="p.provider"></i> {{p.title}}</h4>
            </a>
            <div ng-if="nope.isIframe" class="pull-right">
              <i class="fa fa-check-circle-o fa-2x" ng-show="!selection.hasItem(p);"></i>
              <i class="fa fa-check-circle fa-2x" ng-show="selection.hasItem(p);"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav class="navbar navbar-fixed-bottom navbar-light bg-faded offset-md-3 offset-sm-3 offset-lg-2 load-more" ng-if="metadata.next>metadata.actual">
      <a href="" class="btn btn-block btn-outline-info" ng-click="search(q,metadata.next)">More</a>
    </nav>
  </div>
  <div class="detail-column" ui-view="content"></div>
</div>
