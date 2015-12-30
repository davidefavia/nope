(function() {
  'use strict';

  angular.module('nope', ['ngMessages', 'ngResource', 'ngSanitize', 'ui.router', 'nope.app']);

  angular.module('nope.app', [
    'nope.ui',
    'ngFileUpload'
  ])
  .constant('BasePath', window.BASE_PATH)
  .constant('AssetsPath', window.TEMPLATES_PATH)
  .constant('RolesList', window.ROLES)
  .config(['$compileProvider', function ($compileProvider) {
    $compileProvider.debugInfoEnabled(false);
  }])
  .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
    $stateProvider
    .state('app', {
      url : '/',
      abstract: true,
      templateUrl : 'view/app.html',
      controller: 'AppController',
      resolve : {
        isLoggedIn : ['User', function(User) {
          return User.getLoginStatus().$promise;
        }]
      }
    })
    .state('login', {
      url :'/login{code:(?:/[^/]+)?}',
      templateUrl : 'view/login.html',
      controller : 'LoginController',
      resolve : {
        isLoggedIn : ['$q', '$state', 'User', function($q, $state, User) {
          var q = $q.defer();
          User.getLoginStatus(function() {
            q.reject();
            $state.go('app.dashboard');
          }, q.resolve);
          return q.promise;
        }]
      }
    })
    .state('app.dashboard', {
      url : 'dashboard',
      views : {
        'content@app' : {
          templateUrl : 'view/dashboard.html',
          //controller: 'DashboardController'
        }
      },
      resolve : {
        UsersList : function(User) {
          return User.getAll().$promise;
        }
      }
    })
    .state('app.user', {
      url : 'user',
      views : {
        'content@app' : {
          templateUrl : 'view/user/list.html',
          controller: 'UserController'
        }
      },
      resolve : {
        UsersList : function(User) {
          return User.getAll().$promise;
        }
      }
    })
    .state('app.user.create', {
      url : '/create',
      views : {
        'content@app.user' : {
          templateUrl : 'view/user/form.html',
          controller : 'UserCreateController'
        }
      }
    })
    .state('app.user.detail', {
      url : '/:id',
      views : {
        'content@app.user' : {
          templateUrl : 'view/user/form.html',
          controller : 'UserDetailController'
        }
      }
    })
    ;
    $urlRouterProvider.otherwise('/login');
  }])
  /**
   * Controllers
   */
   .controller('AppController', ['$scope', '$rootScope', '$state', 'AssetsPath', 'User', function($scope, $rootScope, $state, AssetsPath, User) {

     $rootScope.logout = function() {
       User.logout(function() {
         $scope.$emit('nope.toast.success', 'Logged out.');
         $state.go('login');
       });
     }

     $rootScope.assetsPath = AssetsPath;

   }])
   .controller('LoginController', ['$scope', '$rootScope', '$state', '$stateParams', 'AssetsPath', 'User', function($scope, $rootScope, $state, $stateParams, AssetsPath, User) {

     $rootScope.assetsPath = AssetsPath;

     $scope.recovery = false;
     $scope.useResetCode = $stateParams.code;
     $scope.resetCode = $stateParams.code.substr(1);

     $scope.login = function() {
       User.login($scope.user, function() {
         $scope.$emit('nope.toast.success', 'Welcome!', {timeout:1000});
         $state.go('app.dashboard');
       }, function() {
         $scope.loginServerError = true;
       });
     }

     $scope.recoveryPassword = function() {
       User.recovery({email:$scope.recoveryEmail}, function() {
         $scope.recoveryStatus = true;
       });
     }

     $scope.resetPassword = function() {
       User.reset({password:$scope.password, confirm: $scope.confirm, code: $scope.resetCode}, function() {
         $scope.$emit('nope.toast.success', 'Welcome!', {timeout:1000});
         $state.go('app.dashboard');
       });
     }

   }])
   .controller('UserController', ['$scope', '$state', '$nopeModal', 'User', 'UsersList', function($scope, $state, $nopeModal, User, UsersList) {
     $scope.usersList = UsersList;

     $scope.deleteUserOnClick = function() {
       User.delete({id:$scope.userToDelete.id}, function() {
         User.getAll(function(data) {
           $scope.$emit('nope.toast.success', 'User deleted.');
           UsersList = data;
           $scope.usersList = UsersList;
           $state.go('app.user');
         });
       });
     }

     $scope.deleteUser = function(u) {
       $scope.userToDelete = u;
       $nopeModal.fromTemplate('<nope-modal title="Delete user">\
       <nope-modal-body><p>Are you sure to delete user "{{userToDelete.getFullName()}}"?</p></nope-modal-body>\
       <nope-modal-footer>\
         <a class="btn btn-default" nope-modal-close>Close</a>\
         <a class="btn btn-danger" ng-click="deleteUserOnClick();">Yes, delete</a>\
       </nope-modal-footer>\
      </nope-modal>', $scope).then(function(modal) {
        modal.show();
      });
     };
   }])
   .controller('UserCreateController', ['$scope', '$state', '$nopeToast', 'RolesList', 'User', 'UsersList', function($scope, $state, $nopeToast, RolesList, User, UsersList) {
     $scope.user = new User();
     $scope.user.email = '';
     $scope.$parent.selectedUser = $scope.user;
     $scope.rolesList = RolesList;

     $scope.save = function() {
       User.save($scope.user, function(data) {
         $scope.$emit('nope.toast.success', 'User created.');
         UsersList.push(data);
         $state.go('app.user.detail', {id:data.id});
       });
     }
   }])
   .controller('UserDetailController', ['$scope', '$filter', '$timeout', '$state', '$stateParams', '$nopeToast', 'RolesList', 'User', 'UsersList', function($scope, $filter, $timeout, $state, $stateParams, $nopeToast, RolesList, User, UsersList) {
     $scope.user = $filter('filter')(UsersList, {id:$stateParams.id})[0];
     $scope.$parent.selectedUser = $scope.user;
     $scope.rolesList = RolesList;
     $scope.changed = false;

     $scope.$watch('user', function(n,o) {
       if(!angular.equals(n,o)) {
          $scope.changed = true;
       }
     }, true);

     $scope.reset = function() {
       $state.go('app.user.detail', {id:$stateParams.id}, {
         reload: true
       });
     }

     $scope.save = function() {
       User.update($scope.user, function(data) {
         $scope.$emit('nope.toast.success', 'User updated.');
         $scope.user = $filter('filter')(UsersList, {id:$stateParams.id})[0];
         //$scope.user = data;
         $timeout(function() {
           $scope.changed = false;
         }, 100);
       });
     }
   }])
   /**
    * Services
    */
    .factory('User', ['$resource', function($resource) {
      var r = $resource('user/:id', {id:'@id'}, {
        login : {
          url: 'user/login',
          method: 'POST'
        },
        getLoginStatus : {
          url : 'user/loginstatus',
          cache : false
        },
        logout : {
          url : 'user/logout',
          cache : false
        },
        getAll : {
          isArray : true
        },
        update : {
          method : 'PUT'
        },
        recovery : {
          url: 'user/recovery',
          method: 'POST'
        },
        reset : {
          url: 'user/reset',
          method: 'POST'
        }
      });

      r.prototype.isAdmin = function() {
        return this.is('admin');
      }

      r.prototype.can = function(p) {
        var section = p.split('.')[0];
        var a = (this.isAdmin() || this.permissions.indexOf(p)!==-1 || this.permissions.indexOf(section + '.*')!==-1);
        return a;
      }

      r.prototype.is = function(role) {
        return this.role === role;
      }

      r.prototype.getFullName = function() {
        return this.prettyName || this.username;
      }

      r.prototype.itsMe = function(u) {
        if(u) {
          return this.id === u.id;
        }
        return false;
      }

      return r;

    }])
   /**
    * Interceptor
    */
   .service('NopeHttpInterceptor', ['$rootScope', '$cacheFactory', '$injector', '$q', function($rootScope, $cacheFactory, $injector, $q) {
     return {
       request : function(request) {
         if(!request.cache) {
           if(!request.params) {
             request.params = {};
           }
           request.params.__t__ = (new Date()).getTime();
         }
         if(/^view\/(.+).html/.test(request.url)) {
           request.url = window.BASE_PATH + request.url;
         } else if(request.url.indexOf('.html')!==-1) {
           request.url = window.TEMPLATES_PATH + request.url;
         } else {
           request.url = window.BASE_PATH + request.url;
         }
         if(request.method.toLowerCase!=='get') {
           $cacheFactory.get('$http').removeAll();
         }
         return request;
       },
       responseError : function(reason) {
         var $state = $injector.get('$state');
         if(reason.status === 401) {
           if(reason.config.url.indexOf('loginstatus')!==-1) {
             return $q.reject(reason);
           } else {
             $state.go('login');
             return $q.reject(reason);
           }
         } else {
           $rootScope.$emit('nope.error', reason);
           return $q.reject(reason);
         }
         return reason;
       },
       response : function(response) {
         if(response.data.currentUser) {
           var User = $injector.get('User');
           $rootScope.currentUser = new User();
           $rootScope.currentUser = angular.extend($rootScope.currentUser, response.data.currentUser);
           response.data = response.data.data;
         }
         return response;
       }
     }
   }])
   .config(['$httpProvider', function($httpProvider) {
     $httpProvider.interceptors.push('NopeHttpInterceptor');
   }])
   /**
    * Run!
    */
   .run(['$rootScope', '$location', '$state', '$nopeModal', '$nopeToast', function($rootScope, $location, $state, $nopeModal, $nopeToast) {

     $rootScope.$on('$stateChangeSuccess', function(e) {
       $rootScope.selectedPath = $location.path();
       //console.log(arguments);
     });

     $rootScope.$on('$stateChangeError', function() {
       $state.go('login');
     });

     $rootScope.$on('nope.error', function(e, reason) {
       $rootScope.errorReason = reason;
       $nopeModal.fromTemplate('<nope-modal title="Error {{errorReason.status}}" nope-modal-close>\
       <nope-modal-body>\
        <p ng-if="!errorReason.data.exception.length">{{errorReason.statusText}}</p>\
        <p ng-if="errorReason.data.exception.length">{{errorReason.data.exception[0].message}}</p>\
      </nope-modal-body>\
       <nope-modal-footer>\
         <a class="btn btn-default" nope-modal-close>Close</a>\
       </nope-modal-footer>\
      </nope-modal>', $rootScope).then(function(modal) {
        modal.show();
      });
     });

     $rootScope.$on('nope.toast.info', function(e, m, o) {
       $nopeToast.success(m, o);
     });

     $rootScope.$on('nope.toast.success', function(e, m, o) {
       $nopeToast.success(m, o);
     });

     $rootScope.$on('nope.toast.warning', function(e, m, o) {
       $nopeToast.warning(m, o);
     });

     $rootScope.$on('nope.toast.error', function(e, m, o) {
       $nopeToast.error(m, o);
     });

   }])


})()
