<form name="userForm" ng-submit="save();">
  <div class="panel panel-default">
    <div class="panel-heading content-author" ng-if="user.id">
      <div class="list-group pull-left">
        <div class="list-group-item clearfix" ng-class="{'has-image':user.cover}">
          <img ng-src="{{user.cover.preview.icon}}" class="img-circle" ng-if="user.cover" />
          <h4 class="fullname">{{user.username}} <small ng-if="user.prettyName">{{user.prettyName}}</small></h4>
        </div>
      </div>
      <div class="btn-group btn-group-xs pull-right toolbar" ng-if="!currentUser.itsMe(user)">
        <a href="" nope-user-delete="$parent.deleteUserOnClick(user);" ng-model="user" class="btn text-danger" ng-if="!currentUser.itsMe(u)"><i class="fa fa-trash"></i></a>
      </div>
    </div>
    <div class="panel-body">
      <div class="form-group" ng-if="!user.id" ng-class="{'has-error':(!userForm.username.$valid && userForm.username.$touched)}">
        <label class="control-label">Username</label>
        <input type="text" name="username" class="form-control" ng-model="user.username" required   ng-pattern='<?php echo \Nope\Utils::USERNAME_REGEX_PATTERN; ?>'' ng-trim="false" />
        <div ng-messages="userForm.username.$error" ng-if="userForm.username.$touched" ng-cloak>
          <span class="help-block" ng-message="required">Username is required.</span>
          <span class="help-block" ng-message="pattern">Username must contain only lower case letters, numbers and it need to be between 3 and 20 chars long.</span>
        </div>
      </div>
      <div class="form-group" ng-class="{'has-error':((!userForm.password.$valid && userForm.password.$touched) || (!userForm.confirm.$valid && userForm.confirm.$touched))}">
        <div class="row">
          <div class="col col-md-6">
              <label class="control-label" ng-if="!user.id">Password</label>
              <label class="control-label" ng-if="user.id">New password</label>
              <input type="password" name="password" class="form-control" ng-model="user.password" ng-required="!user.id" />
              <div ng-messages="userForm.password.$error" ng-if="userForm.password.$touched" ng-cloak>
                <span class="help-block" ng-message="required">Password is required.</span>
              </div>
          </div>
          <div class="col col-md-6">
              <label class="control-label" ng-if="!user.id">Confirm password</label>
              <label class="control-label" ng-if="user.id">Confirm new password</label>
              <input type="password" name="confirm" class="form-control" ng-model="user.confirm" ng-required="!user.id" nope-match="user.password" />
              <div ng-messages="userForm.confirm.$error" ng-if="userForm.confirm.$touched" ng-cloak>
                <span class="help-block" ng-message="required">Password confirmation is required.</span>
                <span class="help-block" ng-message="match">Password and its confirmation must match.</span>
              </div>
          </div>
        </div>
      </div>
      <div class="form-group" ng-class="{'has-error':(!userForm.email.$valid && userForm.email.$touched)}">
        <label class="control-label">Email</label>
        <input type="text" ng-pattern='<?php echo \Nope\Utils::EMAIL_REGEX_PATTERN; ?>' name="email" class="form-control" ng-model="user.email" required />
        <div ng-messages="userForm.email.$error" ng-if="userForm.email.$touched" ng-cloak>
         <span class="help-block" ng-message="required">Email is required.</span>
         <span class="help-block" ng-message="pattern">Email must be valid.</span>
       </div>
      </div>
      <div class="form-group">
        <label>Pretty name</label>
        <input type="text" name="prettyname" class="form-control" ng-model="user.prettyName" />
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" ng-model="user.enabled" ng-true-value="1" ng-false-value="0"  ng-disabled="!currentUser.isAdmin() || currentUser.id===user.id"> Enabled
        </label>
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
        <nope-model href="#/media?mimetype=image/" ng-model="user.cover" multiple="false" label="Add cover" preview="icon"></nope-model>
      </div>
    </div>
    <div class="panel-footer">
      <button class="btn btn-block" ng-disabled="userForm.$invalid" ng-class="{'btn-success':!userForm.$invalid}">Save</button>
    </div>
  </div>
</form>
