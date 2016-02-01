(function() {
  'use strict';
  angular.module('nope.app')
    .config(['$stateProvider', function($stateProvider) {
      $stateProvider
        .state('app.content', {
          url: 'content/:contentType',
          views: {
            'content@app': {
              templateUrl: function($stateParams) {
                return 'view/' + $stateParams.contentType + '/list.html';
              },
              controller: 'ContentsListController'
            }
          }
        })
        .state('app.content.detail', {
          url: '/view/{id:int}',
          views: {
            'content@app.content': {
              templateUrl: function($stateParams) {
                return 'view/' + $stateParams.contentType + '/detail.html'
              },
              controller: 'ContentDetailController'
            }
          }
        })
        .state('app.content.create', {
          url: '/create',
          views: {
            'content@app': {
              templateUrl: function($stateParams) {
                return 'view/' + $stateParams.contentType + '/form.html'
              },
              controller: 'ContentCreateController'
            }
          }
        })
        .state('app.content.edit', {
          url: '/edit/{id:int}',
          views: {
            'content@app': {
              templateUrl: function($stateParams) {
                return 'view/' + $stateParams.contentType + '/form.html'
              },
              controller: 'ContentEditController'
            }
          }
        });
    }])
    /**
     * Controller
     */
    .controller('ContentsListController', ['$scope', '$rootScope', '$location', '$state', '$stateParams', '$nopeModal', '$nopeUtils', 'Content', function($scope, $rootScope, $location, $state, $stateParams, $nopeModal, $nopeUtils, Content) {
      $scope.contentType = $stateParams.contentType;
      $scope.contentsList = [];
      $scope.q = $location.search();

      $scope.selection = [];

      $scope.select = function(c,i) {
        if($rootScope.nope.isIframe) {
          var callerScope = $nopeUtils.getContentModalCallerScope();
          $scope.selection = callerScope.selectedItem(c);
          callerScope.$apply();
        } else {
          $state.go('app.content.detail', {
            id:c.id,
            contentType: $stateParams.contentType
          });
        }
      }

      $scope.search = function(q, page) {
        page = page || 1;
        Content.query(angular.extend({
          type: $stateParams.contentType,
          page: page
        }, q), function(data, headers) {
          $scope.metadata = angular.fromJson(headers().link);
          $scope.contentsList = (page===1?[]:$scope.contentsList).concat(data);
        });
      }

      $scope.search($scope.q);

      $scope.deleteContentOnClick = function(p) {
        var title = p.title;
        return Content.delete({
          type: $stateParams.contentType,
          id: p.id
        }, function() {
          $scope.$emit('nope.toast.success', 'Content "'+title+'" deleted.');
          $state.go('app.content', {
            type: $stateParams.contentType
          }, {
            reload: true
          });
        });
      }

      $scope.save = function(p, i) {
        Content.update({
          type: $stateParams.contentType
        }, p, function(data) {
          $scope.$emit('nope.toast.success', 'Content "' + data.title + '" updated.');
          if(i===undefined) {
            angular.forEach($scope.contentsList, function(item,index) {
              if(item.id===data.id) {
                i = index;
              }
            });
          }
          if(i!==undefined) {
            $scope.contentsList[i] = data;
          } else {
            // Needed to avoid strange reordering due to 'starred' content sorted before than others.
            $scope.search($scope.q);
          }
          $scope.$broadcast('nope.content.updated', data);
        });
      }

    }])
    .controller('ContentCreateController', ['$scope', '$rootScope', '$state', '$stateParams', 'Content', function($scope, $rootScope, $state, $stateParams, Content) {
      $scope.content = new Content();
      $scope.content.author = $rootScope.currentUser;
      $scope.content.format = 'html';
      $scope.content.status = 'draft';

      $scope.save = function() {
        Content.save({
          type: $stateParams.contentType
        }, $scope.content, function(data) {
          $scope.$emit('nope.toast.success', 'Content "' + data.title + '" created.');
          $state.go('app.content.edit', {
            contentType: $stateParams.contentType,
            id: data.id
          });
        });
      }
    }])
    .controller('ContentEditController', ['$scope', '$stateParams', 'Content', function($scope, $stateParams, Content) {
      Content.get({
        type: $stateParams.contentType,
        id: $stateParams.id
      }, function(data) {
        $scope.content = data;
      });

      $scope.save = function() {
        Content.update({
          type: $stateParams.contentType
        }, $scope.content, function(data) {
          $scope.$emit('nope.toast.success', 'Content "' + data.title + '" updated.');
          $scope.content = data;
        });
      }

      $scope.getRealStatus = function() {
        if (!$scope.content.id) {
          return;
        }
        Content.getCalculatedStatus({
          type: $stateParams.contentType,
          id: $scope.content.id,
          status: $scope.content.status,
          startPublishingDate: $scope.content.startPublishingDate,
          endPublishingDate: $scope.content.endPublishingDate
        }, function(data) {
          $scope.content.realStatus = data.realStatus;
        });
      }

    }])
    .controller('ContentDetailController', ['$scope', '$stateParams', 'Content', function($scope, $stateParams, Content) {

      Content.get({
        type: $stateParams.contentType,
        id: $stateParams.id
      }, function(data) {
        $scope.content = data;
        $scope.$parent.selectedContent = $scope.content;
      });

      $scope.$on('nope.content.updated', function(e, data) {
        if(data.id === $stateParams.id) {
          $scope.content = data;
          $scope.$parent.selectedContent = $scope.content;
        }
      });
    }])
    /**
     * Services
     */
    .service('Content', ['$resource', function($resource) {
      return $resource('content/:type/:id', {
        type: '@type',
        id: '@id'
      }, {
        update: {
          method: 'PUT'
        },
        getCalculatedStatus: {
          url: 'content/:type/:id/status'
        }
      });
    }]);
})();
