(function() {
  'use strict';
  angular.module('nope.app')
    .config(['$stateProvider', function($stateProvider) {
      $stateProvider
        .state('app.menu', {
          url: 'menu',
          views: {
            'content@app': {
              templateUrl: 'view/menu/list.html',
              controller: 'MenusListController'
            }
          }
        })
        .state('app.menu.create', {
          url: '/create',
          views: {
            'content': {
              templateUrl: 'view/menu/form.html',
              controller: 'MenuCreateController'
            }
          }
        })
        .state('app.menu.detail', {
          url: '/view/{id:int}',
          views: {
            'content': {
              templateUrl: 'view/menu/form.html',
              controller: 'MenuDetailController'
            }
          }
        });
    }])
    /**
     * Controller
     */
    .controller('MenusListController', ['$scope', '$state', 'Menu', function($scope, $state, Menu) {

      $scope.search = function() {
        Menu.query(function(data) {
          $scope.menusList = data;
        });
      }

      $scope.search();

      $scope.deleteContentOnClick = function(p) {
        var title = p.title;
        return Menu.delete({
          id: p.id
        }, function() {
          $scope.$emit('nope.toast.success', 'Menu "' + title + '" deleted.');
          $state.go('app.menu', {}, {
            reload: true
          });
        });
      }

      $scope.save = function(p, i) {
        Menu.update(p, function(data) {
          $scope.$emit('nope.toast.success', 'Menu "' + data.title + '" updated.');
          if(i===undefined) {
            angular.forEach($scope.menusList, function(item,index) {
              if(item.id===data.id) {
                i = index;
              }
            });
          }
          if(i!==undefined) {
            $scope.menusList[i] = data;
          }
          $scope.$broadcast('nope.menu.updated', data);
        });
      }

    }])
    .controller('MenuCreateController', ['$scope', '$state', 'Menu', function($scope, $state, Menu) {
      $scope.menu = new Menu();
      $scope.menu.items = [];

      $scope.save = function() {
        Menu.save($scope.menu, function(data) {
          $scope.$emit('nope.toast.success', 'Menu "'+data.title+'" created.');
          $state.go('app.menu.detail', {
            id: data.id
          }, {
            reload: true
          });
        });
      }

    }])
    .controller('MenuDetailController', ['$scope', '$stateParams', 'Menu', function($scope, $stateParams, Menu) {

      Menu.get({
        id: $stateParams.id
      }, function(data) {
        $scope.menu = data;
        $scope.$parent.$parent.selectedMenu = $scope.menu;
      });

      $scope.$on('nope.menu.updated', function(e, data) {
        if(data.id === $stateParams.id) {
          $scope.menu = data;
          $scope.$parent.$parent.selectedMenu = $scope.menu;
        }
      });

      $scope.save = function(m) {
        $scope.$parent.save(m);
      }

    }])
    /**
     * Services
     */
    .service('Menu', ['$resource', function($resource) {
      return $resource('menu/:id', {
        id: '@id'
      }, {
        update: {
          method: 'PUT'
        }
      });
    }]);
})();
