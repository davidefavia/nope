<div class="row">
  <div id="model" class="col col-md-3 col-sm-4">
    <div class="form-group" nope-can="user.read">
      <input type="text" class="form-control" ng-model="q.username" placeholder="Filter users by username" />
    </div>
    <div class="list-group">
      <div class="list-group-item ng-cloak" ng-show="!filteredUsersList.length">No users found with filter "{{q.username}}".</div>
      <div class="list-group-item clearfix" ng-class="{active:u.itsMe(selectedUser)}" ng-repeat="u in filteredUsersList = (usersList | filter : q)" ng-show="filteredUsersList.length">
        <h4 class="list-group-item-heading"><i class="fa small" ng-class="{'fa-ban text-danger':!u.enabled,'fa-check text-success':u.enabled}"></i> {{u.username}}</h4>
        <p class="list-group-item-text" ng-if="u.pretty_name">{{u.pretty_name}}</p>
        <div class="btn-group btn-group-xs pull-right toolbar">
          <a ng-href="#/user/{{u.id}}" class="btn btn-default"><i class="fa fa-pencil"></i></a>
          <a href="" ng-click="deleteUser(u);" class="btn btn-default" ng-if="!currentUser.itsMe(u)" nope-can="user.delete"><i class="fa fa-trash text-danger"></i></a>
        </div>
      </div>
    </div>
    <a href="#/user/create" class="btn btn-sm btn-block btn-default" nope-can="user.create">Create new user <i class="fa fa-plus"></i></a>
  </div>
  <div class="col col-md-9 col-sm-8" ui-view="content">
    <no-empty icon="user">Select user</no-empty>
  </div>
</div>