(function() {
  angular.module('nope', ['ngResource', 'ngSanitize', 'ui.router', 'app']);

  angular.module('app', [])
  .config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
    $stateProvider
    .state('app', {
      url : '/',
      abstract: true
    })
    .state('login', {
      url :'/login',
      templateUrl : 'assets/tmpl/login.html',
      controller : 'LoginController'
    })
    ;
    $urlRouterProvider.otherwise('/login');
  }])
  /**
   * Controllers
   */
   .controller('LoginController', ['$scope', '$state', 'User', function($scope, $state, User) {

     $scope.login = function() {
       User.login($scope.user, function() {
         $state.go('app.dashboard');
       });
     }

   }])
   /**
    * Services
    */
    .factory('User', ['$resource', function($resource) {
      return $resource('user', {}, {
        login : {
          url: 'user/login',
          method: 'POST'
        }
      });
    }])
   /**
    * Interceptor
    */
   .service('NopeHttpInterceptor', [function() {
     return {
       request : function(request) {
         if(request.url.indexOf('.html')!==-1) {
           request.url = window.TEMPLATES_PATH + request.url;
         } else {
           request.url = window.BASE_PATH + request.url;
         }
         return request;
       }
     }
   }])
   .config(['$httpProvider', function($httpProvider) {
     $httpProvider.interceptors.push('NopeHttpInterceptor');
   }])


})()
