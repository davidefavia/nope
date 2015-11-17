(function() {
  angular.module('nope', ['ngResource', 'ngSanitize', 'ui.router', 'app']);

  angular.module('app', [])
  .constant('BasePath', window.BASE_PATH)
  .constant('AssetsPath', window.TEMPLATES_PATH)
  .constant('RolesList', window.ROLES)
  .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
    $stateProvider
    .state('app', {
      url : '/',
      abstract: true,
      templateUrl : 'assets/tmpl/app.html',
      controller: 'AppController',
      resolve : {
        isLoggedIn : function(User) {
          return User.getLoginStatus().$promise;
        }
      }
    })
    .state('login', {
      url :'/login',
      templateUrl : 'assets/tmpl/login.html',
      controller : 'LoginController'
    })
    .state('app.user', {
      url : 'user',
      views : {
        'content@app' : {
          templateUrl : 'assets/tmpl/user.html',
          controller: 'UserController'
        }
      },
      resolve : {
        usersList : function(User) {
          return User.getAll().$promise;
        }
      }
    })
    .state('app.user.create', {
      url : '/create',
      views : {
        'content@app.user' : {
          templateUrl : 'assets/tmpl/user-detail.html',
          controller : 'UserCreateController'
        }
      }
    })
    .state('app.user.detail', {
      url : '/:id',
      views : {
        'content@app.user' : {
          templateUrl : 'assets/tmpl/user-detail.html',
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
   .controller('AppController', ['$scope', '$rootScope', 'AssetsPath', 'User', function($scope, $rootScope, AssetsPath, User) {

     $rootScope.logout = function() {
       User.logout();
     }

     $rootScope.assetsPath = AssetsPath;

   }])
   .controller('LoginController', ['$scope', '$rootScope', '$state', 'AssetsPath', 'User', function($scope, $rootScope, $state, AssetsPath, User) {

     $rootScope.assetsPath = AssetsPath;

     $scope.login = function() {
       User.login($scope.user, function() {
         $state.go('app.user');
       });
     }

   }])
   .controller('UserController', ['$scope', 'usersList', function($scope, usersList) {
     $scope.usersList = usersList;
   }])
   .controller('UserCreateController', ['$scope', '$state', 'RolesList', 'User', 'usersList', function($scope, $state, RolesList, User, usersList) {
     $scope.user = new User();
     $scope.$parent.selectedUser = $scope.user;
     $scope.rolesList = RolesList;

     $scope.save = function() {
       User.save($scope.user, function(data) {
         usersList.push(data);
         $state.go('app.user.detail', {id:data.id});
       });
     }
   }])
   .controller('UserDetailController', ['$scope', '$filter', '$state', '$stateParams', 'RolesList', 'User', 'usersList', function($scope, $filter, $state, $stateParams, RolesList, User, usersList) {
     $scope.user = $filter('filter')(usersList, {id:$stateParams.id})[0];
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
         $scope.user = data;
         $scope.changed = false;
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
        }
      });

      r.prototype.isAdmin = function() {
        return this.is('admin');
      }

      r.prototype.can = function(p) {
        return (this.isAdmin() || this.permissions.indexOf(p)!==-1);
      }

      r.prototype.is = function(role) {
        return this.role === role;
      }

      r.prototype.getFullName = function() {
        return this.pretty_name || this.username;
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
   .service('NopeHttpInterceptor', ['$rootScope', '$injector', '$q', function($rootScope, $injector, $q) {
     return {
       request : function(request) {
         if(!request.cache) {
           if(!request.params) {
             request.params = {};
           }
           request.params.__t__ = (new Date()).getTime();
         }
         if(request.url.indexOf('.html')!==-1) {
           request.url = window.TEMPLATES_PATH + request.url;
         } else {
           request.url = window.BASE_PATH + request.url;
         }
         return request;
       },
       responseError : function(reason) {
         var $state = $injector.get('$state');
         if(reason.status === 401) {
           $state.go('login');
         } else {
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
    * Directives
    */
   .directive('noEmpty', [function() {
     return {
       restrict : 'E',
       transclude : true,
       replace: true,
       template : '<div class="empty"><i class="fa fa-{{icon}}"><h3 ng-transclude></h3></div>',
       scope : {
         icon : '@'
       }
     }
   }])
   /**
    * Run!
    */
   .run(['$rootScope', '$location', function($rootScope, $location) {

     $rootScope.$on('$stateChangeSuccess', function(e) {
       $rootScope.selectedPath = $location.path();
     });

   }])


})()
