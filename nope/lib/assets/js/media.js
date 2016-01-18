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
          },
          resolve : {
            MediaList : ['$location', 'Media', function($location, Media) {
              return Media.query($location.search()).$promise;
            }]
          }
        })
        .state('app.media.detail', {
          url: '/view/:id',
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
    .controller('MediaListController', ['$scope', '$rootScope', '$location', '$window', '$state', '$stateParams', '$nopeModal', 'Media', 'MediaList', function($scope, $rootScope, $location, $window, $state, $stateParams, $nopeModal, Media, MediaList) {
      $scope.contentType = 'media';
      $scope.selectedMedia = null;
      $scope.selectedMediaIndex = null;
      $scope.contentsList = MediaList;

      if($location.search()) {
        $scope.q = $location.search();
        $scope.acceptedFiles = $location.search().mimetype + '*';
        $scope.hideMimetypeOptions = true;
      }

      $scope.selection = [];

      $scope.select = function(c,i) {
        if($rootScope.nope.isIframe) {
          var $parent = $window.parent;
          var el = $parent.angular.element($parent.document.getElementById('modal-content'));
          var callerScope = el.isolateScope().$parent;
          $scope.selection = callerScope.selectedItem(c);
          callerScope.$apply();
        } else {
          $scope.selectedMediaIndex = i;
          $state.go('app.media.detail', {id:c.id});
        }
      }

      $scope.deleteContentOnClick = function() {
        var t = $scope.contentToDelete.title;
        Media.delete({
          id: $scope.contentToDelete.id
        }, function() {
          $scope.$emit('nope.toast.success', 'Media "'+t+'" deleted.');
          $scope.theModal.hide();
          if($scope.selectedMedia && $scope.selectedMedia.id === $scope.contentToDelete.id) {
            $scope.selectedMedia = null;
            $scope.selectedMediaIndex = null;
            $state.go('app.media');
          }
          $scope.getAllContents();
        });
      }

      $scope.deleteContent = function(c) {
        $scope.contentToDelete = c;
        $nopeModal.fromTemplate('<nope-modal title="Delete media">\
      <nope-modal-body><p>Are you sure to delete media "{{contentToDelete.title}}"?</p></nope-modal-body>\
      <nope-modal-footer>\
        <a class="btn btn-default" nope-modal-close>Close</a>\
        <a class="btn btn-danger" ng-click="deleteContentOnClick();">Yes, delete</a>\
      </nope-modal-footer>\
     </nope-modal>', $scope).then(function(modal) {
         $scope.theModal = modal;
         $scope.theModal.show();
        });
      };

      $scope.onUploadDone = function() {
        $scope.$emit('nope.toast.success', 'Media created.');
        $scope.getAllContents();
      }

      $scope.getAllContents = function() {
        if($location.search()) {
          $scope.q = $location.search();
        } else {
          $scope.q = {};
        }
        $scope.search($scope.q, 1);
      }

      $scope.search = function(q, page) {
        page = page || 1;
        if(page===1) {
          $scope.contentsList = [];
        }
        Media.query(angular.extend({
          page : page
        }, q), function(data, headers) {
          $scope.metadata = angular.fromJson(headers().link);
          $scope.contentsList = $scope.contentsList.concat(data);
          MediaList = $scope.contentsList;
        });
      }

      $scope.save = function(p, i) {
        Media.update(p, function(data) {
          var msg = (data.starred ? 'starred': 'unstarred');
          $scope.$emit('nope.toast.success', 'Media "'+data.title+'" '+msg+'.');
          $scope.$broadcast('nope.media.updated', data);
          $scope.contentsList[i] = data;
          MediaList = $scope.contentsList;
        });
      }

    }])
    .controller('MediaDetailController', ['$scope', '$filter', '$state', '$stateParams', 'Media', function($scope, $filter, $state, $stateParams, Media) {
      var mediaId = parseInt($stateParams.id,10);
      var i = 0;

      $scope.media = $filter('filter')($scope.$parent.contentsList, {
        id: mediaId
      }, function(actual, expected, index) {
        if(actual === expected) {
          $scope.$parent.selectedMediaIndex = i;
          return true;
        }
        i++
      })[0];
      $scope.$parent.selectedMedia = $scope.media;

      $scope.save = function() {
        Media.update($scope.media, function(data) {
          $scope.$emit('nope.toast.success', 'Media "'+data.title+'" updated.');
          $scope.media = data;
          $scope.$parent.selectedMedia = data;
          $scope.$parent.contentsList[$scope.$parent.selectedMediaIndex] = data;
        });
      }

      $scope.$on('nope.media.updated', function(e, data) {
        if(data.id === mediaId) {
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
        }
      });
    }]);
})();
