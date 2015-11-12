(function() {
  angular.module('nope', ['ngResource', 'ngSanitize', 'ui.router', 'app']);

  angular.module('app', [])
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
   .controller('UserController', ['$scope', function($scope) {

   }])
   /**
    * Services
    */
    .factory('User', ['$resource', function($resource) {
      return $resource('user', {}, {
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
        }
      });
    }])
   /**
    * Interceptor
    */
   .service('NopeHttpInterceptor', ['$injector', function($injector) {
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
         }
         return reason;
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
