<div id="menu" class="row">
  <div class="list-column col col-md-4 col-sm-6">
    <div class="searchbar">
      <div class="form-group" nope-can="menu.read">
        <input type="text" class="form-control" ng-model="q" placeholder="Filter" />
      </div>
      <a href="#/menu/create" class="btn btn-sm btn-block btn-default" nope-can="menu.create" ng-click="selectedGallery=null;">Create new menu <i class="fa fa-plus"></i></a>
    </div>
    <div class="list-group">
      <div class="list-group-item ng-cloak" ng-show="!filteredMenusList.length">No menu found<span ng-show="q"> with filter "{{q}}"</span></span>.</div>
      <div class="list-group-item clearfix" ng-class="{active:p.id===selectedMenu.id}" ng-repeat="p in filteredMenusList = (menusList | filter : q)" ng-show="menusList.length">
        <a ng-href="#/menu/view/{{p.id}}"><h4 class="list-group-item-heading">{{p.title}}</h4></a>
        <p ng-if="p.body" class="list-group-item-text">{{p.body}}</p>
        <div class="btn-group btn-group-xs pull-right toolbar">
          <a ng-href="#/menu/view/{{p.id}}" class="btn"><i class="fa fa-pencil"></i></a>
          <a href="" nope-content-delete="deleteContentOnClick(p);" ng-model="p" class="btn text-danger"><i class="fa fa-trash"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="col col-md-8 col-sm-6 col-content" ui-view="content">
    <nope-empty icon="bars">
      <span ng-if="menusList.length">Select menu</span>
      <a href="#/menu/create" ng-if="!filteredMenusList.length" class="btn btn-default" nope-can="menu.create" ng-click="selectedMenu=null;">Create new menu <i class="fa fa-plus"></i></a>
    </nope-empty>
  </div>
</div>
