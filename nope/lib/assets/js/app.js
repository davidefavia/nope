(function() {
  'use strict';

  angular.module('nope', ['ngMessages', 'ngResource', 'ngSanitize', 'ui.router', 'nope.app']);

  angular.module('nope.app', [
    'nope.ui',
    'ngFileUpload',
    'dndLists'
  ])
  .constant('BasePath', window.NOPE_BASE_PATH)
  .constant('AssetsPath', window.NOPE_TEMPLATES_PATH)
  .constant('RolesList', window.NOPE_USER_ROLES)
  .constant('TextFormatsList', window.NOPE_TEXT_FORMATS)
  .constant('DefaultTextFormat', window.NOPE_DEFAULT_TEXT_FORMAT)
  .constant('Iframe', window.NOPE_IFRAME)
  .constant('IframeCaller', window.NOPE_IFRAME_CALLER)
  .config(['$compileProvider', function ($compileProvider) {
    // Required to be true in order to communicate between iframes.
    $compileProvider.debugInfoEnabled(true);
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
      }
    })
    .state('app.user', {
      url : 'user',
      views : {
        'content@app' : {
          templateUrl : 'view/user/list.html',
          controller: 'UsersListController'
        }
      },
      resolve : {
        UsersList : function(User) {
          return User.query().$promise;
        }
      }
    })
    .state('app.user.create', {
      url : '/create',
      views : {
        'content' : {
          templateUrl : 'view/user/form.html',
          controller : 'UserCreateController'
        }
      }
    })
    .state('app.user.detail', {
      url : '/view/{id:int}',
      views : {
        'content' : {
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
   .controller('AppController', ['$scope', '$rootScope', '$state', 'AssetsPath', 'Iframe', 'IframeCaller', 'TextFormatsList', 'User', function($scope, $rootScope, $state, AssetsPath, Iframe, IframeCaller, TextFormatsList, User) {

     $rootScope.logout = function() {
       User.logout(function() {
         $scope.$emit('nope.toast.success', 'Logged out.');
         $state.go('login');
       });
     }

     $rootScope.assetsPath = AssetsPath;

     $rootScope.textFormats = TextFormatsList;

     $rootScope.nope = {
       isIframe : Iframe,
       iframeCaller : IframeCaller
     }

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
   .controller('UsersListController', ['$scope', '$rootScope', '$location', '$state', '$nopeModal', '$nopeUtils', 'User', function($scope, $rootScope, $location, $state, $nopeModal, $nopeUtils, User) {
     $scope.usersList = [];
     $scope.q = $location.search();

     $scope.search = function(q, page) {
       page = page || 1;
       User.query(angular.extend({
         page : page
       }, q), function(data, headers) {
         $scope.metadata = angular.fromJson(headers().link);
         $scope.usersList = (page===1?[]:$scope.usersList).concat(data);
       });
     }

     $scope.search($scope.q);

     $scope.deleteUserOnClick = function(p) {
       var username = p.username;
       return User.delete({
         id: p.id
       }, function() {
         $scope.$emit('nope.toast.success', 'User "' + username + '" deleted.');
         $state.go('app.user', {}, {
           reload: true
         });
       });
     }

     $scope.save = function(p, i) {
       User.update(p, function(data) {
         $scope.$emit('nope.toast.success', 'User "' + data.username + '" updated.');
         if(i===undefined) {
           angular.forEach($scope.usersList, function(item,index) {
             if(item.id===data.id) {
               i = index;
             }
           })
         }
         if(i!==undefined) {
           $scope.usersList[i] = data;
         } else {
           $scope.search($scope.q);
         }
         $scope.$broadcast('nope.user.updated', data);
       });
     }
   }])
   .controller('UserCreateController', ['$scope', '$state', '$nopeToast', 'RolesList', 'User', function($scope, $state, $nopeToast, RolesList, User) {
     $scope.user = new User();
     $scope.user.email = '';
     $scope.$parent.selectedUser = $scope.user;
     $scope.rolesList = RolesList;

     $scope.save = function() {
       User.save($scope.user, function(data) {
         $scope.$emit('nope.toast.success', 'User "'+data.username+'" created.');
         $state.go('app.user.detail', {id:data.id});
       });
     }
   }])
   .controller('UserDetailController', ['$scope', '$filter', '$timeout', '$state', '$stateParams', '$nopeToast', 'RolesList', 'User', function($scope, $filter, $timeout, $state, $stateParams, $nopeToast, RolesList, User) {
     $scope.rolesList = RolesList;

     User.get({
       id: $stateParams.id
     }, function(data) {
       $scope.user = data;
       $scope.$parent.$parent.selectedUser = $scope.user;
     });

     $scope.$on('nope.user.updated', function(e, data) {
       if(data.id === $stateParams.id) {
         $scope.user = data;
         $scope.$parent.$parent.selectedUser = $scope.user;
       }
     });

     $scope.save = function() {
       $scope.$parent.save($scope.user);
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
   .service('NopeHttpInterceptor', ['$rootScope', '$cacheFactory', '$injector', '$q', 'BasePath', 'AssetsPath', function($rootScope, $cacheFactory, $injector, $q, BasePath, AssetsPath) {
     return {
       request : function(request) {
         var $nopeLoading = $injector.get('$nopeLoading');
         $nopeLoading.show();
         if(!request.cache) {
           if(!request.params) {
             request.params = {};
           }
           request.params.__t__ = (new Date()).getTime();
         }
         if(/^view\/(.+).html/.test(request.url)) {
           request.url = BasePath + request.url;
         } else if(request.url.indexOf('.html')!==-1) {
           request.url = AssetsPath + request.url;
         } else {
           request.url = BasePath + request.url;
         }
         if(request.method.toLowerCase!=='get') {
           $cacheFactory.get('$http').removeAll();
         }
         return request;
       },
       responseError : function(reason) {
         var $state = $injector.get('$state');
         var $nopeLoading = $injector.get('$nopeLoading');
         $nopeLoading.remove();
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
           if($rootScope.currentUser.cover) {
             $rootScope.currentUser.cover.preview.profile = $rootScope.currentUser.cover.preview.profile + '?__t__=' + (new Date()).getTime();
           }
           response.data = response.data.data;
         }
         var $nopeLoading = $injector.get('$nopeLoading');
         $nopeLoading.hide();
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
       $nopeModal.fromTemplate('<nope-modal title="Error {{errorReason.status}}">\
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
