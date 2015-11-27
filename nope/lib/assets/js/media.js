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
            MediaList : ['Media', function(Media) {
              return Media.getAll().$promise;
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
    .controller('MediaListController', ['$scope', '$state', '$stateParams', '$nopeModal', 'Media', 'MediaList', function($scope, $state, $stateParams, $nopeModal, Media, MediaList) {
      $scope.contentType = 'media';
      $scope.selectedMedia = null;
      $scope.contentsList = MediaList;

      $scope.deleteContentOnClick = function() {
        Media.delete({
          id: $scope.contentToDelete.id
        }, function() {
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
          modal.show();
        });
      };

      $scope.onUploadDone = function() {
        $scope.getAllContents();
      }

      $scope.getAllContents = function() {
        Media.getAll(function(data) {
          $scope.contentsList = data;
          MediaList = $scope.contentsList;
        });
      }

    }])
    .controller('MediaDetailController', ['$scope', '$filter', '$state', '$stateParams', 'Media', 'MediaList', function($scope, $filter, $state, $stateParams, Media, MediaList) {
      $scope.media = $filter('filter')(MediaList, {
        id: parseInt($stateParams.id,10)
      }, true)[0];
      $scope.$parent.selectedMedia = $scope.media;
    }])
    /**
     * Services
     */
    .service('Media', ['$resource', function($resource) {
      return $resource('content/media/:id', {
        id: '@id'
      }, {
        getAll: {
          isArray: true
        },
        update: {
          method: 'PUT'
        }
      });
    }]);
})();
