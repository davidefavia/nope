<!DOCTYPE html>
<html ng-app="app">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link href="<?php echo path('lib/assets/css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo path('lib/assets/css/app.min.css'); ?>" rel="stylesheet" />
  </head>
  <body>
    <div class="container front">
      <div class="row">
        <div class="col-md-offset-3 col-md-6">
          <div class="logo">
            <img src="<?php echo path('lib/assets/img/nope.png'); ?>" />
          </div>
          <div class="panel panel-default">
            <div class="panel-body">
              <form method="POST" action="<?php echo adminRoute('install'); ?>" name="installationForm">
                <ol class="breadcrumb">
                  <li <?php if($step===1) { ?>class="active"<?php } ?>>1. Check requirements</li>
                  <li <?php if($step===2) { ?>class="active"<?php } ?>>2. User info</li>
                  <li>3. Install</li>
                </ol>
                <div class="list-group">
                  <?php if($step === 1 || !$ok ) { ?>
                    <?php foreach($requirements as $key => $requirement) { ?>
                      <div class="list-group-item list-group-item-<?php echo ($requirement->passed?'success':'danger'); ?>">
                        <h4 class="list-group-item-heading">
                          <?php if($requirement->icon) { ?>
                            <i class="fa fa-<?php echo $requirement->icon; ?>"></i>
                          <?php } ?>
                          <?php echo $requirement->title; ?>
                          <i class="pull-right fa fa-<?php if($requirement->passed) { ?>check<?php } else { ?>exclamation<?php } ?>"></i></h4>
                        <?php foreach($requirement->lines as $line) { ?>
                          <p class="list-group-item-text"><?php echo $line; ?></p>
                        <?php } ?>
                        <?php if($requirement->help) { ?>
                          <p class="help-block"><i class="fa fa-info-circle"></i> <?php echo $requirement->help; ?></p>
                        <?php } ?>
                      </div>
                    <?php } ?>
                  <?php } ?>
                </div>
                <?php if($step === 2 && $ok ) { ?>
                  <?php if($user===false) { ?>
                  <p>Something wrong.</p>
                  <?php } ?>
                  <div class="form-group" ng-class="{'has-error':(!installationForm.username.$valid && installationForm.username.$touched)}">
                    <label class="control-label">Username:</label>
                    <input type="text" name="username" ng-model="user.username" class="form-control" placeholder="Username" required ng-pattern='<?php echo \Nope\Utils::USERNAME_REGEX_PATTERN; ?>' ng-trim="false" />
                    <div ng-messages="installationForm.username.$error" ng-if="installationForm.username.$touched" ng-cloak>
                      <span class="help-block" ng-message="required">Username is required.</span>
                      <span class="help-block" ng-message="pattern">Username must contain only lower case letters, numbers and it need to be between 3 and 20 chars long.</span>
                    </div>
                  </div>
                  <div class="form-group" ng-class="{'has-error':(!installationForm.password.$valid && installationForm.password.$touched)}">
                    <label class="control-label">Password:</label>
                    <input type="password" name="password" ng-model="user.password" class="form-control" placeholder="Password" required />
                    <div ng-messages="installationForm.password.$error" ng-if="installationForm.password.$touched" ng-cloak>
                      <span class="help-block" ng-message="required">Password is required.</span>
                    </div>
                  </div>
                  <div class="form-group" ng-class="{'has-error':(!installationForm.confirm.$valid && installationForm.confirm.$touched)}">
                    <label class="control-label">Confirm password:</label>
                    <input type="password" name="confirm" ng-model="user.confirm" class="form-control" placeholder="Confirm password" required nope-match="user.password" />
                    <div ng-messages="installationForm.confirm.$error" ng-if="installationForm.confirm.$touched" ng-cloak>
                      <span class="help-block" ng-message="required">Password confirmation is required.</span>
                      <span class="help-block" ng-message="match">Password and its confirmation must match.</span>
                    </div>
                  </div>
                  <div class="form-group" ng-class="{'has-error':(!installationForm.email.$valid && installationForm.email.$touched)}">
                    <label class="control-label">Email:</label>
                    <input type="email" name="email" ng-model="user.email" class="form-control" placeholder="Email" required ng-pattern='<?php echo \Nope\Utils::EMAIL_REGEX_PATTERN; ?>' />
                    <div ng-messages="installationForm.email.$error" ng-if="installationForm.email.$touched" ng-cloak>
                     <span class="help-block" ng-message="required">Email is required.</span>
                     <span class="help-block" ng-message="pattern">Email must be valid.</span>
                   </div>
                  </div>
                <?php } ?>
                <div class="form-group">
                  <?php if($step === 2 ) { ?>
                  <button type="submit" class="btn btn-success btn-block btn-lg" ng-disabled="installationForm.$invalid">Install</button>
                  <?php } else { ?>
                    <?php if($ok) { ?>
                      <button class="btn btn-primary btn-block btn-lg">Go forward</button>
                    <?php } else { ?>
                      <a href="" class="btn btn-warning btn-block btn-lg">Check again</a>
                    <?php } ?>
                  <?php } ?>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
      <script src="<?php echo path('lib/assets/js/lib/angular.min.js'); ?>"></script>
      <script src="<?php echo path('lib/assets/js/lib/angular-messages.min.js'); ?>"></script>
      <script>
        angular.module('app', ['ngMessages'])
        .run(['$rootScope', function($rootScope) {
          $rootScope.user = {};
        }])
        .directive('nopeMatch', [function() {
          return {
            restrict : 'A',
            require : '^ngModel',
            scope : {
              nopeMatch : '=',
              ngModel : '='
            },
            link : function($scope, $element, $attrs, ngModelCtrl) {
              $scope.$watch('ngModel', function(n,o) {
                ngModelCtrl.$setValidity('match',(n===$scope.nopeMatch));
              }, true);
              $scope.$watch('nopeMatch', function(n,o) {
                ngModelCtrl.$setValidity('match',($scope.ngModel===n));
              }, true);
            }
          }
        }])
        ;
      </script>
  </body>
</html>
