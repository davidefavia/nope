<div id="media" class="row" ng-class="{'has-detail':selectedMedia}">
  <div class="col list-column" ng-class="{'col-md-9 col-sm-8':selectedMedia}">
    <div class="searchbar">
      <form name="searchForm" ng-submit="search(q);">
        <div class="input-group" nope-can="media.read">
          <input type="text" class="form-control" ng-model="q.query" placeholder="Search" />
          <div class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
          </div>
          <div class="input-group-btn" nope-can="media.create">
            <a href="" nope-upload-modal="onUploadDone()" accept="{{acceptedFiles}}" class="btn btn-block btn-default">Create new media <i class="fa fa-upload"></i></a>
          </div>
        </div>
        <div class="form-group">
          <div ng-hide="hideMimetypeOptions">
            <label class="radio-inline">
              <input type="radio" ng-model="q.mimetype" value=""> All
            </label>
            <label class="radio-inline">
              <input type="radio" ng-model="q.mimetype" value="image/"> Only images
            </label>
            <label class="radio-inline">
              <input type="radio" ng-model="q.mimetype" value="!image/"> Not images
            </label>
            <label class="radio-inline">
              <input type="radio" ng-model="q.mimetype" value="provider"> Only from providers
            </label>
          </div>
        </div>
      </form>
    </div>
    <div class="media-list list-group">
      <div class="list-group-item ng-cloak" ng-show="!contentsList.length && (q.query || q.mimetype)">No media found with filter "{{q}}".</div>
      <div ng-if="!contentsList.length">
        <nope-empty icon="upload">
          <a href="" nope-upload="onUploadDone()" class="btn btn-default" nope-can="media.create">Upload <i class="fa fa-plus"></i></a>
        </nope-empty>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-4" ng-repeat="p in contentsList" ng-show="contentsList.length">
          <div class="card card--media" ng-class="{active:p.id===selectedMedia.id}">
            <a ng-href="#/media/view/{{p.id}}" nope-content-selection="p" ng-model="selection" class="btn-select">
              <div class="background" style="{{'background-image:url('+p.preview.thumb+');'+(p.palette?'background-color:rgb('+[p.palette[0][0],p.palette[0][1],p.palette[0][2]].join(',')+');':'')}}">
                <h4 class="card-title"><i class="fa {{'fa-'+(p.provider | lowercase)}}" ng-if="p.provider"></i> {{p.title}}</h4>
              </div>
            </a>
            <div class="card-block">
              <div ng-if="nope.isIframe" class="pull-right">
                <i class="fa fa-check-circle-o fa-2x" ng-show="!selection.hasItem(p);"></i>
                <i class="fa fa-check-circle fa-2x" ng-show="selection.hasItem(p);"></i>
              </div>
              <div class="btn-group btn-group-xs toolbar pull-right" ng-if="!nope.isIframe">
                <a ng-click="p.starred=!p.starred;save(p,$index);" class="btn"><i class="fa" ng-class="{'fa-star-o':!p.starred,'fa-star':p.starred}"></i></a>
                <a href="" class="btn" ng-click="rotate(p,90,$index);" ng-if="p.isImage"><i class="fa fa-rotate-left"></i></a>
                <a href="" class="btn" ng-click="rotate(p,-90,$index);" ng-if="p.isImage"><i class="fa fa-rotate-right"></i></a>
                <a href="" nope-zoom="p.url" class="btn" ng-if="p.isImage"><i class="fa fa-arrows-alt"></i></a>
                <a href="" class="btn text-danger" nope-content-delete="deleteContentOnClick(p);" ng-model="p"><i class="fa fa-trash"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <a href="" class="btn btn-sm btn-block btn-default" ng-click="search(q,metadata.next)" ng-if="metadata.next>metadata.actual">More</a>
  </div>
  <div class="detail-column col col-md-3 col-sm-4" ng-show="selectedMedia" ui-view="content">
    <nope-empty icon="file-text-o">Select media</nope-empty>
  </div>
</div>
