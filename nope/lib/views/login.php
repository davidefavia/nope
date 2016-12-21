<div class="container front login">
  <div class="row">
    <div class="col-md-offset-4 col-md-4">
      <div class="logo">
        <img ng-src="{{assetsPath + 'assets/img/nope.png'}}" />
      </div>
      <div class="panel panel-default">
        <div class="panel-body" ng-class="{recovery:recovery}">
          <div ng-hide="recovery || useResetCode">
            <form class="form-signin" name="loginForm" ng-submit="login()">
              <div class="form-group" ng-class="{'has-error':(!loginForm.username.$valid && loginForm.username.$touched) || loginServerError}">
                <label class="control-label">Username</label>
                <input name="username" class="form-control input-lg" placeholder="Username" required type="text" ng-model="user.username" />
              </div>
              <div class="form-group" ng-class="{'has-error':(!loginForm.password.$valid && loginForm.password.$touched) || loginServerError}">
                <label class="control-label">Password</label>
                <input name="password" class="form-control input-lg" placeholder="Password" required type="password" ng-model="user.password" />
              </div>
              <div class="form-group clearfix">
                <a ng-click="recovery=true" class="btn btn-link btn-lg">Forgot password</a>
                <button class="btn btn-lg pull-right" type="submit" ng-class="{'btn-success':!loginForm.$invalid}" ng-disabled="loginForm.$invalid">Sign in</button>
              </div>
            </form>
          </div>
          <div ng-show="recovery">
            <form class="form-signin" ng-show="!recoveryStatus" name="recoveryForm" ng-submit="recoveryPassword()">
              <div class="form-group" ng-class="{'has-error':(!recoveryForm.email.$valid && recoveryForm.email.$touched)}">
                <label class="control-label">Subscription email</label>
                <input name="email" autofocus class="form-control input-lg" placeholder="Email" required type="email" ng-pattern='<?php echo \Nope\Utils::EMAIL_REGEX_PATTERN; ?>' ng-model="recoveryEmail" />
                <div ng-messages="recoveryForm.email.$error" ng-if="recoveryForm.email.$touched" ng-cloak>
                 <span class="help-block" ng-message="required">Subscription email is required.</span>
                 <span class="help-block" ng-message="pattern">Subscription email must be valid.</span>
               </div>
              </div>
              <div class="form-group clearfix">
                <a ng-click="recovery=false;recoveryStatus=false" class="btn btn-link btn-lg">&laquo; Go to login</a>
                <button class="btn btn-lg pull-right" type="submit" ng-class="{'btn-success':!recoveryForm.$invalid}" ng-disabled="recoveryForm.$invalid">Send</button>
              </div>
            </form>
            <div ng-show="recoveryStatus">
              <p>
                A message has beeen sent to your subscription email address. Check it out.
              </p>
              <a ng-click="recovery=false;recoveryStatus=false;recoveryEmail=null;recoveryForm.email.$setUntouched();" class="btn btn-link btn-lg">&laquo; Go to login</a>
            </div>
          </div>
          <div ng-show="useResetCode">
            <form class="form-signin" name="resetPasswordForm" ng-submit="resetPassword()">
              <div class="form-group" ng-class="{'has-error':(!resetPasswordForm.password.$valid && resetPasswordForm.password.$touched)}">
                <label class="control-label">New password:</label>
                <input type="password" name="password" ng-model="password" class="form-control input-lg" placeholder="Password" required />
                <div ng-messages="resetPasswordForm.password.$error" ng-if="resetPasswordForm.password.$touched" ng-cloak>
                  <span class="help-block" ng-message="required">Password is required.</span>
                </div>
              </div>
              <div class="form-group" ng-class="{'has-error':(!resetPasswordForm.confirm.$valid && resetPasswordForm.confirm.$touched)}">
                <label class="control-label">Confirm new password:</label>
                <input type="password" name="confirm" ng-model="confirm" class="form-control input-lg" placeholder="Confirm password" required nope-match="password" />
                <div ng-messages="resetPasswordForm.confirm.$error" ng-if="resetPasswordForm.confirm.$touched" ng-cloak>
                  <span class="help-block" ng-message="required">Password confirmation is required.</span>
                  <span class="help-block" ng-message="match">Password and its confirmation must match.</span>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success btn-block btn-lg" ng-disabled="resetPasswordForm.$invalid">Reset password and login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <h6 class="version">v<?php echo NOPE_VERSION; ?></h6>
    </div>
  </div>
</div>
