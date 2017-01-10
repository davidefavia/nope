<div id="gallery" class="row tmpl-searchbar tmpl-detail-column" ng-class="{'has-detail':selectedGallery}">
  <div class="backdrop" ng-click="closeDetail();"></div>
  <div class="col list-column" ng-class="{'has-more':metadata.next>metadata.actual}">
    <nav class="navbar fixed-top navbar-light offset-md-3 offset-sm-3 offset-lg-2 searchbar" nope-can="gallery.read">
      <form name="searchForm" ng-submit="search(q);">
        <div class="row">
          <div class="col-8">
            <input type="text" class="form-control" ng-model="q.query" placeholder="Search" />
          </div>
          <div class="col">
            <button type="submit" class="btn btn-secondary btn-info"><i class="fa fa-search"></i></button>
          </div>
          <div class="col" nope-can="gallery.create" ng-if="!nope.isIframe">
            <a href="#/gallery/create" class="btn btn-outline-danger" ng-click="selectedGallery=null;">Create new gallery <i class="fa fa-plus"></i></a>
          </div>
        </div>
      </form>
    </nav>
    <div class="info-text clearfix">
      <p class="pagination-text text-xs-left text-muted pull-left" ng-show="contentsList.length"><a href="" class="text-primary" ng-click="bulkSelection=[].concat(contentsList);">Select all</a> | <a href="" class="text-primary" ng-click="bulkSelection=[];">Clear selection</a><span ng-show="bulkSelection.length">. Selected items: {{bulkSelection.length}}. With selected: <a href="" class="text-danger" nope-content-delete="deleteBulkContentOnClick(p);" ng-model="bulkSelection">delete</a> or <a href="" class="text-info" ng-click="bulkEditTags();">edit tags</a>.</span></p>
      <p ng-show="contentsList.length" class="pagination-text text-xs-right text-muted pull-right">Items {{metadata.actual===metadata.last?metadata.count:metadata.actual*metadata.rpp}} of {{metadata.count}}</p>
      <p class="pagination-text ng-cloak no-results" ng-show="!contentsList.length"><i class="fa fa-frown-o"></i>No gallery found<span ng-if="q.query || q.mimetype"><br/>with filter <span ng-if="q.query">"{{q.query}}"</span><span ng-if="q.query && q.mimetype"> and </span><span ng-if="q.mimetype">"{{q.mimetype}}"</span></span>.</p>
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-3" ng-repeat="p in contentsList" ng-show="contentsList.length">
        <div class="card card--media" ng-class="{active:p.id===selectedGallery.id,'card--selected':bulkSelection.hasItem(p)}">
          <div class="card-image-block">
            <nope-lazy src="p.cover.preview.thumb"></nope-lazy>
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
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <nav class="navbar fixed-bottom navbar-light bg-faded offset-md-3 offset-sm-3 offset-lg-2 load-more" ng-if="metadata.next>metadata.actual">
      <a href="" class="btn btn-block btn-outline-info" ng-click="search(q,metadata.next)">More</a>
    </nav>
  </div>
  <div class="detail-column" ui-view="content"></div>
</div>
