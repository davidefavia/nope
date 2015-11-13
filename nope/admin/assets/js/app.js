(function() {
  angular.module('nope', ['ngResource', 'ngSanitize', 'ui.router', 'app']);

  angular.module('app', [])
  .constant('rolesList', window.ROLES)
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
   .controller('AppController', ['$scope', '$rootScope', 'User', function($scope, $rootScope, User) {

     $rootScope.logout = function() {
       User.logout();
     }

   }])
   .controller('LoginController', ['$scope', '$state', 'User', function($scope, $state, User) {

     $scope.login = function() {
       User.login($scope.user, function() {
         $state.go('app.user');
       });
     }

   }])
   .controller('UserController', ['$scope', 'usersList', function($scope, usersList) {
     $scope.usersList = usersList;
   }])
   .controller('UserCreateController', ['$scope', '$state', 'rolesList', 'User', 'usersList', function($scope, $state, rolesList, User, usersList) {
     $scope.user = new User();
     $scope.rolesList = rolesList;

     $scope.save = function() {
       User.save($scope.user, function(data) {
         usersList.push(data);
         $state.go('app.user.detail', {id:data.id});
       });
     }
   }])
   .controller('UserDetailController', ['$scope', '$filter', '$stateParams', 'rolesList', 'User', 'usersList', function($scope, $filter, $stateParams, rolesList, User, usersList) {
     $scope.user = $filter('filter')(usersList, {id:$stateParams.id})[0];
     $scope.rolesList = rolesList;

     $scope.save = function() {
       User.update($scope.user, function() {

       });
     }
   }])
   /**
    * Services
    */
    .factory('User', ['$resource', function($resource) {
      return $resource('user/:id', {id:'@id'}, {
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
           $rootScope.currentUser = response.data.currentUser;
           response.data = response.data.data;
         }
         return response;
       }
     }
   }])
   .config(['$httpProvider', function($httpProvider) {
     $httpProvider.interceptors.push('NopeHttpInterceptor');
   }])
   .run(['$rootScope', function($rootScope) {

     $rootScope.$on('$stateChangeError', function(e) {

     });

   }])


})()
