<div class="container front">
  <div class="row">
    <div class="col-md-offset-4 col-md-4">
      <div class="logo">
        <img ng-src="{{assetsPath + 'assets/img/nope.png'}}" />
      </div>
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-signin" name="loginForm" ng-submit="login()">
            <div class="form-group">
              <label for="inputUsername">Username</label>
              <input autofocus class="form-control input-lg" id="inputUsername" placeholder="Username" required type="text" ng-model="user.username">
            </div>
            <div class="form-group">
              <label for="inputPassword">Password</label>
              <input class="form-control input-lg" id="inputPassword" placeholder="Password" required type="password" ng-model="user.password">
            </div>
            <div class="form-group">
              <button class="btn btn-lg btn-block" type="submit" ng-class="{'btn-success':!loginForm.$invalid}" ng-disabled="loginForm.$invalid">Sign in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
