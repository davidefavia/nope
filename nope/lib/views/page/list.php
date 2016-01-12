<div class="row">
  <div class="list-column col col-md-4 col-sm-6">
    <div class="searchbar">
      <form name="searchForm" ng-submit="searchByText(q);">
        <div class="input-group" nope-can="{{contentType}}.read">
          <input type="text" class="form-control" ng-model="q.query" placeholder="Search" />
          <div class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
            <a class="btn btn-default" ng-click="showOptions=!showOptions"><span class="caret"></span></a>
            <ul ng-show="showOptions" class="dropdown-menu dropdown-menu-right" ng-cloak>
              <li ng-class="{active:q.status==='draft'}">
                <a href="" ng-click="q.status=(q.status==='draft'?null:'draft');showOptions=false;">Only draft</a>
              </li>
              <li ng-class="{active:q.status==='published'}">
                <a href="" ng-click="q.status=(q.status==='published'?null:'published');showOptions=false;">Only published</a>
              </li>
            </ul>
          </div>
        </div>
      </form>
      <a href="#/content/page/create" class="btn btn-sm btn-block btn-default" nope-can="{{contentType}}.create">Create new {{contentType}} <i class="fa fa-plus"></i></a>
    </div>
    <div class="list-group">
      <div class="list-group-item ng-cloak" ng-show="!contentsList.length && q">No {{contentType}} found with filter "{{q.query}}" <span ng-show="q.status">status "{{q.status}}"</span>.</div>
      <div class="list-group-item clearfix media" ng-class="{active:p.id===selectedContent.id}" ng-repeat="p in contentsList" ng-show="contentsList.length">
        <div class="media-left" ng-if="p.cover">
          <img class="media-object img-circle" ng-src="{{p.cover.preview.icon}}" alt="...">
        </div>
        <div class="media-body">
          <a ng-href="#/content/{{contentType}}/view/{{p.id}}"><h4 class="list-group-item-heading">{{::p.title}}</h4></a>
          <p class="list-group-item-text">
            <nope-publishing ng-model="p"></nope-publishing>
          </p>
          <div class="btn-group btn-group-xs pull-right toolbar">
            <a ng-href="#/content/{{contentType}}/edit/{{p.id}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
            <a href="" ng-click="deleteContent(p);" class="btn btn-default" nope-can="page.delete"><i class="fa fa-trash"></i></a>
          </div>
        </div>
      </div>
    </div>
    <a href="" class="btn btn-sm btn-block btn-default" ng-click="searchByText(q,metadata.next)" ng-if="metadata.next>metadata.actual">More</a>
  </div>
  <div class="detail-column col" ng-class="{'col-md-8 col-sm-6':contentsList.length}" ui-view="content">
    <no-empty icon="file-text-o">
      <span ng-if="contentsList.length">Select {{contentType}}</span>
      <a href="#/content/page/create" class="btn btn-default" nope-can="user.create" ng-if="!contentsList.length">Create new {{contentType}} <i class="fa fa-plus"></i></a>
    </no-empty>
  </div>
</div>
