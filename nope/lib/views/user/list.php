<div id="user" class="row">
  <div class="list-column col" ng-class="{'col-md-4 col-sm-6':!nope.isIframe}">
    <div class="searchbar">
      <form name="searchForm" ng-submit="search(q);">
        <div class="input-group" nope-can="user.read">
          <input type="text" class="form-control" ng-model="q.query" placeholder="Search" />
          <div class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>
      <a ng-if="!nope.isIframe" href="#/user/create" class="btn btn-sm btn-block btn-default" nope-can="user.create">Create new user <i class="fa fa-plus"></i></a>
    </div>
    <div class="list-group">
      <div class="list-group-item ng-cloak" ng-show="!usersList.length && q.query">No users found with filter "{{q.query}}".</div>
      <div class="list-group-item clearfix media" ng-class="{active:u.itsMe(selectedUser)}" ng-repeat="u in usersList" ng-show="usersList.length">
        <div class="media-left" ng-if="u.cover">
          <img class="media-object img-circle" ng-src="{{u.cover.preview.icon}}" alt="...">
        </div>
        <div class="media-body">
          <a ng-href="#/user/view/{{u.id}}" ng-model="selection" nope-content-selection="u"><h4 class="list-group-item-heading"><i class="fa small" ng-class="{'fa-ban text-danger':!u.enabled,'fa-check text-success':u.enabled}"></i> {{u.username}}</h4></a>
          <p class="list-group-item-text" ng-if="u.prettyName">{{u.prettyName}}</p>
          <div ng-if="nope.isIframe" class="pull-right">
            <i class="fa fa-check-circle-o fa-2x" ng-show="!selection.hasItem(u);"></i>
            <i class="fa fa-check-circle fa-2x" ng-show="selection.hasItem(u);"></i>
          </div>
          <div ng-if="!nope.isIframe" class="btn-group btn-group-xs pull-right toolbar">
            <a ng-href="#/user/view/{{u.id}}" ng-model="selection" nope-content-selection="u" class="btn"><i class="fa fa-pencil"></i></a>
            <a href="" nope-user-delete="deleteUserOnClick(u);" ng-model="u" class="btn text-danger" ng-if="!currentUser.itsMe(u)"><i class="fa fa-trash"></i></a>
          </div>
        </div>
      </div>
    </div>
    <a href="" class="btn btn-sm btn-block btn-default" ng-click="search(q,metadata.next)" ng-if="metadata.next>metadata.actual">More</a>
  </div>
  <div ng-if="!nope.isIframe" class="col col-md-8 col-sm-6" ui-view="content">
    <no-empty icon="user">Select user</no-empty>
  </div>
</div>
