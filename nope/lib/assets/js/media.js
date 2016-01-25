(function() {
  'use strict';
  angular.module('nope.app')
    .config(['$stateProvider', function($stateProvider) {
      $stateProvider
        .state('app.media', {
          url: 'media',
          views: {
            'content@app': {
              templateUrl: 'view/media/list.html',
              controller: 'MediaListController'
            }
          }
        })
        .state('app.media.detail', {
          url: '/view/{id:int}',
          views: {
            'content': {
              templateUrl: 'view/media/form.html',
              controller: 'MediaDetailController'
            }
          }
        });
    }])
    /**
     * Controller
     */
    .controller('MediaListController', ['$scope', '$rootScope', '$location', '$window', '$state', '$stateParams', '$nopeModal', '$nopeUtils', 'Media', function($scope, $rootScope, $location, $window, $state, $stateParams, $nopeModal, $nopeUtils, Media) {
      $scope.contentType = 'media';
      $scope.contentsList = [];
      $scope.q = $location.search();
      if($location.search().mimetype) {
        $scope.acceptedFiles = $location.search().mimetype + '*';
        $scope.hideMimetypeOptions = true;
      }

      $scope.selection = [];

      $scope.select = function(c,i) {
        if($rootScope.nope.isIframe) {
          var callerScope = $nopeUtils.getContentModalCallerScope();
          $scope.selection = callerScope.selectedItem(c);
          callerScope.$apply();
        } else {
          $state.go('app.media.detail', {id:c.id});
        }
      }

      $scope.deleteContentOnClick = function(p) {
        var t = p.title;
        Media.delete({
          id: p.id
        }, function() {
          $scope.$emit('nope.toast.success', 'Media "'+t+'" deleted.');
          $state.go('app.media', {}, {
            reload: true
          });
        });
      }

      $scope.onUploadDone = function() {
        $scope.$emit('nope.toast.success', 'Media created.');
        $scope.q = {};
        $scope.search($scope.q);
      }

      $scope.search = function(q, page) {
        page = page || 1;
        Media.query(angular.extend({
          page : page
        }, q), function(data, headers) {
          $scope.metadata = angular.fromJson(headers().link);
          $scope.contentsList = (page===1?[]:$scope.contentsList).concat(data);
          angular.forEach($scope.contentsList, function(value, index) {
            $scope.contentsList[index].preview.thumb = $scope.contentsList[index].preview.thumb + '?_t_=' + (new Date()).getTime();
          });
        });
      }

      $scope.search($scope.q);

      $scope.save = function(p, i) {
        Media.update(p, function(data) {
          $scope.$emit('nope.toast.success', 'Media "'+data.title+'" updated.');
          if(i===undefined) {
            angular.forEach($scope.contentsList, function(item,index) {
              if(item.id===data.id) {
                i = index;
              }
            })
          }
          if(i!==undefined) {
            $scope.contentsList[i] = data;
          } else {
            // Needed to avoid strange reordering due to 'starred' content sorted before than others.
            $scope.search($scope.q);
          }
          $scope.$broadcast('nope.media.updated', data);
        });
      }

      $scope.$on('nope.media.image.edited', function(e,data) {
        angular.forEach($scope.contentsList, function(item,index) {
          if(item.id===data.id) {
            $scope.contentsList[index].preview.thumb = data.preview.thumb;
          }
        });
      });

    }])
    .controller('MediaDetailController', ['$scope', '$filter', '$state', '$stateParams', 'Media', function($scope, $filter, $state, $stateParams, Media) {

      Media.get({
        id: $stateParams.id
      }, function(data) {
        $scope.media = data;
        $scope.$parent.selectedMedia = $scope.media;
      });

      $scope.$on('nope.media.updated', function(e, data) {
        if(data.id === $stateParams.id) {
          $scope.media = data;
          $scope.$parent.selectedMedia = $scope.media;
        }
      });

      $scope.$on('nope.media.image.edited', function(e, data) {
        if(data.id === $stateParams.id) {
          $scope.media.preview.thumb = data.preview.thumb;
        }
      });
    }])
    /**
     * Services
     */
    .service('Media', ['$resource', function($resource) {
      return $resource('content/media/:id', {
        id: '@id'
      }, {
        update: {
          method: 'PUT'
        },
        import : {
          method: 'POST',
          url : 'content/media/import'
        },
        editImage : {
          method : 'PUT',
          url : 'content/media/:id/edit',
          params : {
            id: '@id',
            rotate : '@rotate'
          }
        }
      });
    }]);
})();
