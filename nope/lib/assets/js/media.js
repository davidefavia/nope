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
      $scope.bulkSelection = [];
      $scope.contentType = 'media';
      $scope.contentsList = [];
      $scope.q = $location.search();
      if($location.search().mimetype) {
        $scope.acceptedFiles = $location.search().mimetype + '*';
        //$scope.hideMimetypeOptions = true;
      }

      $scope.deleteBulkContentOnClick = function() {
        var idsList = $scope.bulkSelection.map(function(item) {
          return item.id;
        });
        return Media.deleteList({
          id: idsList.join(',')
        }, function() {
          $scope.$emit('nope.toast.success', 'Media deleted.');
          $scope.search($scope.q);
        });
      }

      $scope.deleteContentOnClick = function(p) {
        var t = p.title;
        return Media.delete({
          id: p.id
        }, function() {
          $scope.$emit('nope.toast.success', 'Media "'+t+'" deleted.');
          $scope.search($scope.q);
        });
      }

      $scope.openDetail = function(p) {
        $state.go('app.media.detail', {
          id: p.id
        }, {
          inherit: true
        }).then(function(){
          $location.search($scope.q);
        });
      }

      $scope.closeDetail = function() {
        $scope.selectedMedia = null;
        $location.path('media');
      }

      $scope.onUploadDone = function() {
        $scope.$emit('nope.toast.success', 'Media created.');
        $scope.q = {};
        $scope.search($scope.q);
      }

      $scope.search = function(q, page) {
        page = page || 1;
        $location.search(q);
        if(page===1) {
          $scope.bulkSelection = [];
        }
        Media.query(angular.extend({
          page : page
        }, q), function(data, headers) {
          $scope.metadata = angular.fromJson(headers().link);
          var l = angular.copy($scope.contentsList).length;
          $scope.contentsList = (page===1?[]:$scope.contentsList).concat(data);
          angular.forEach($scope.contentsList, function(value, index) {
            // Refresh only new previews
            if(index>=l) {
              $scope.contentsList[index].preview.thumb = $scope.contentsList[index].preview.thumb.split('?_t_=')[0] + '?_t_=' + (new Date()).getTime();
            }
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
            $location.search($scope.q);
          }
          $scope.$broadcast('nope.media.updated', data);
        });
      }

      $scope.rotate = function(p, d, i) {
        Media.editImage({
          rotate : d,
          id : p.id
        }, p, function(data) {
          var t = '?__t__=' + (new Date()).getTime();
          data.preview.thumb = data.preview.thumb + t;
          data.url = data.url + t;
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
          }
          $scope.$broadcast('nope.media.updated', data);
        });
      }

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
        },
        deleteList: {
          method: 'DELETE',
          url: 'content/media/list'
        }
      });
    }]);
})();
