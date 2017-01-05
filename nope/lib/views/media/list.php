<div id="media" class="row tmpl-searchbar" ng-class="{'has-detail':selectedMedia}">
  <div class="backdrop" ng-click="closeDetail();"></div>
  <div class="col-xs list-column" ng-class="{'has-more':metadata.next>metadata.actual}">
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
            <a href="" nope-upload-modal="onUploadDone()" accept="{{acceptedFiles}}" class="btn btn-outline-danger">Create new media <i class="fa fa-upload"></i></a>
          </div>
        </div>
      </form>
    </nav>
    <div class="clearfix">
      <p class="pagination-text text-xs-left text-muted pull-left" ng-show="contentsList.length"><a href="" class="text-primary" ng-click="bulkSelection=[].concat(contentsList);">Select all</a> | <a href="" class="text-primary" ng-click="bulkSelection=[];">Clear selection</a><span ng-show="bulkSelection.length">. Selected items: {{bulkSelection.length}}. With selected: <a href="" class="text-danger" nope-content-delete="deleteBulkContentOnClick(p);" ng-model="bulkSelection">delete</a>.</span></p>
      <p ng-show="contentsList.length" class="pagination-text text-xs-right text-muted pull-right">Items {{metadata.actual===metadata.last?metadata.count:metadata.actual*metadata.rpp}} of {{metadata.count}}</p>
      <p class="pagination-text text-muted ng-cloak" ng-show="!contentsList.length && (q.query || q.mimetype)">No media found with filter "{{q}}".</p>
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
              <a href="" nope-zoom="p" class="btn text-white" ng-if="p.isImage"><i class="fa fa-arrows-alt"></i></a>
              <a href="" class="btn text-white" ng-click="p.showInfo=!p.showInfo"><i class="fa fa-info"></i></a>
              <a href="" class="btn text-danger" nope-content-delete="deleteContentOnClick(p);" ng-model="p"><i class="fa fa-trash"></i></a>
            </div>
            <div ng-if="!nope.isIframe" class="selection">
              <a href="" class="btn" ng-click="bulkSelection.toggleItem(p);" ng-class="{'btn-selected':bulkSelection.hasItem(p)}">
                <i class="fa fa-check-circle fa-2x"></i>
              </a>
            </div>
            <div ng-if="nope.isIframe" class="selection">
              <i class="fa fa-check-circle-o fa-2x" ng-show="!selection.hasItem(p);"></i>
              <i class="fa fa-check-circle fa-2x" ng-show="selection.hasItem(p);"></i>
            </div>
          </div>
          <div class="card-block">
            <a href="" ng-click="openDetail(p)" nope-content-selection="p" ng-model="selection" class="btn-select card-link">
              <h4 class="card-title"><i class="fa {{'fa-'+(p.provider | lowercase)}}" ng-if="p.provider"></i> {{p.title}} <i class="fa fa-pencil"></i></h4>
              <p class="text-muted"><i class="fa fa-clock-o"></i> {{p.creationDate | nopeMoment: 'calendar'}}</p>
            </a>
            <div ng-if="nope.isIframe" class="pull-right">
              <i class="fa fa-check-circle-o fa-2x" ng-show="!selection.hasItem(p);"></i>
              <i class="fa fa-check-circle fa-2x" ng-show="selection.hasItem(p);"></i>
            </div>
          </div>
          <div class="card-info" ng-class="{opened:p.showInfo}">
            <a href="" class="btn btn-sm btn-light text-muted" ng-click="p.showInfo=false;"><i class="fa fa-close"></i></a>
            <table class="table table-sm">
              <tbody>
                <tr>
                  <th>Creation date</th>
                  <td>{{p.creationDate}}</td>
                </tr>
                <tr>
                  <th>Last update date</th>
                  <td>{{p.lastModificationDate}}</td>
                </tr>
                <tr>
                  <th>Filename</th>
                  <td>{{p.filename}}</td>
                </tr>
                <tr ng-if="p.type && !p.provider">
                  <th>Type</th>
                  <td>{{p.type}}</td>
                </tr>
                <tr ng-if="!p.provider">
                  <th>Mimetype</th>
                  <td>{{p.mimetype}}</td>
                </tr>
                <tr ng-if="!p.provider">
                  <th>Size</th>
                  <td>{{p.size | nopeBites}}</td>
                </tr>
                <tr ng-if="p.width">
                  <th>Width</th>
                  <td>{{p.height}}px</td>
                </tr>
                <tr ng-if="p.height">
                  <th>Height</th>
                  <td>{{p.height}}px</td>
                </tr>
                <tr ng-if="p.provider">
                  <th>Provider</th>
                  <td>{{p.provider}}</td>
                </tr>
              </tbody>
            </table>
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
