<div class="row">
  <div class="list-column col col-md-3 col-sm-4">
    <div class="searchbar">
      <div class="form-group" nope-can="user.read">
        <input type="text" class="form-control" ng-model="q.username" placeholder="Filter users by username" />
      </div>
      <a href="#/user/create" class="btn btn-sm btn-block btn-default" nope-can="user.create">Create new user <i class="fa fa-plus"></i></a>
    </div>
    <div class="list-group">
      <div class="list-group-item ng-cloak" ng-show="!filteredUsersList.length">No users found with filter "{{q.username}}".</div>
      <div class="list-group-item clearfix media" ng-class="{active:u.itsMe(selectedUser)}" ng-repeat="u in filteredUsersList = (usersList | filter : q)" ng-show="filteredUsersList.length">
        <div class="media-left" ng-if="u.cover">
          <img class="media-object img-circle" ng-src="{{u.cover.preview.icon}}" alt="...">
        </div>
        <div class="media-body">
          <a ng-href="#/user/{{u.id}}"><h4 class="list-group-item-heading"><i class="fa small" ng-class="{'fa-ban text-danger':!u.enabled,'fa-check text-success':u.enabled}"></i> {{u.username}}</h4></a>
          <p class="list-group-item-text" ng-if="u.prettyName">{{u.prettyName}}</p>
          <div class="btn-group btn-group-xs pull-right toolbar">
            <a ng-href="#/user/{{u.id}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
            <a href="" ng-click="deleteUser(u);" class="btn btn-default" ng-if="!currentUser.itsMe(u)" nope-can="user.delete"><i class="fa fa-trash"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="detail-column col col-md-9 col-sm-8" ui-view="content">
    <no-empty icon="user">Select user</no-empty>
  </div>
</div>
