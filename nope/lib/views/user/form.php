<div id="user-detail">
  <div class="panel panel-default">
    <div class="panel-body">
      <form name="userForm" ng-submit="save()">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" class="form-control" ng-model="user.username" required  ng-if="!user.id" />
          <p class="form-control-static" ng-if="user.id">{{user.username}}</p>
        </div>
        <div class="form-group" ng-if="!user.id">
          <label>Password</label>
          <input type="password" name="password" class="form-control" ng-model="user.password" required />
        </div>
        <div class="form-group" ng-if="!user.id">
          <label>Confirm password</label>
          <input type="password" name="confirm" class="form-control" ng-model="user.confirm" required />
        </div>
        <div class="form-group">
          <label>Pretty name</label>
          <input type="text" name="prettyname" class="form-control" ng-model="user.pretty_name" />
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" ng-model="user.enabled" ng-true-value="1" ng-false-value="0"  ng-disabled="!currentUser.isAdmin() || currentUser.id===user.id"> Enabled
          </label>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control" ng-model="user.email" />
        </div>
        <div class="form-group">
          <label>Role</label>
          <select name="role" class="form-control" ng-model="user.role" required ng-if="currentUser.isAdmin() && !currentUser.itsMe(user)" nope-can="user.update" ng-disabled="!currentUser.isAdmin() || currentUser.itsMe(user)">
            <option ng-repeat="r in rolesList" value="{{r.key}}" ng-if="r.key!=='admin'">{{r.label}}</option>
          </select>
          <p class="form-control-static" ng-repeat="r in rolesList" ng-if="(!currentUser.isAdmin() || currentUser.isAdmin() && currentUser.itsMe(user)) && user.is(r.key)">{{r.label}}</p>
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea name="description" class="form-control" ng-model="user.description"></textarea>
        </div>
        <div class="form-group">
          <label>Cover</label>
          <nope-model model="Media" ng-model="user.cover" preview="icon" multiple="false"></nope-model>
        </div>
        <div class="form-group clearfix">
          <div class="pull-right">
            <a href="" class="btn btn-warning" ng-if="changed" ng-click="reset();">Reset changes</a>
            <button class="btn" ng-disabled="userForm.$invalid" ng-class="{'btn-success':!userForm.$invalid}">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
