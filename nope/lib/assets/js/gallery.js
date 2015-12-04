(function() {
  'use strict';
  angular.module('nope.app')
    .config(['$stateProvider', function($stateProvider) {
      $stateProvider
        .state('app.gallery', {
          url: 'gallery',
          views: {
            'content@app': {
              templateUrl: 'view/gallery/list.html',
              controller: 'GalleriesListController'
            }
          },
          resolve : {
            GalleriesList : ['Gallery', function(Gallery) {
              return Gallery.getAll().$promise;
            }]
          }
        })
        .state('app.gallery.create', {
          url: '/create',
          views: {
            'content': {
              templateUrl: 'view/gallery/form.html',
              controller: 'GalleryCreateController'
            }
          }
        })
        .state('app.gallery.detail', {
          url: '/view/:id',
          views: {
            'content': {
              templateUrl: 'view/gallery/form.html',
              controller: 'GalleryDetailController'
            }
          }
        })
        ;
    }])
    /**
     * Controller
     */
    .controller('GalleriesListController', ['$scope', '$q', '$state', '$stateParams', '$nopeModal', 'Gallery', 'GalleriesList', function($scope, $q, $state, $stateParams, $nopeModal, Gallery, GalleriesList) {
      $scope.contentType = 'gallery';
      $scope.selectedGallery = null;
      $scope.contentsList = GalleriesList;

      $scope.deleteContentOnClick = function() {
        Gallery.delete({
          id: $scope.contentToDelete.id
        }, function() {
          $scope.$emit('nope.toast.success', 'Gallery deleted.');
          if($scope.selectedGallery && $scope.selectedGallery.id === $scope.contentToDelete.id) {
            $scope.selectedGallery = null;
          }
          $scope.getAllContents();
        });
      }

      $scope.deleteContent = function(c) {
        $scope.contentToDelete = c;
        $nopeModal.fromTemplate('<nope-modal title="Delete gallery">\
      <nope-modal-body><p>Are you sure to delete gallery "{{contentToDelete.title}}"?</p></nope-modal-body>\
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
        var q = $q.defer();
        Gallery.getAll(function(data) {
          $scope.contentsList = data;
          GalleriesList = $scope.contentsList;
          q.resolve();
        });
        return q.promise;
      }

    }])
    .controller('GalleryCreateController', ['$scope', '$state', 'Gallery', function($scope, $state, Gallery) {
      $scope.gallery = new Gallery();

      $scope.save = function() {
        Gallery.save($scope.gallery, function(data) {
          $scope.$emit('nope.toast.success', 'Gallery created.');
          $state.go('app.gallery.detail', {id:data.id}, {reload:true});
        });
      }
    }])
    .controller('GalleryDetailController', ['$scope', '$filter', '$state', '$stateParams', 'Gallery', 'GalleriesList', function($scope, $filter, $state, $stateParams, Gallery, GalleriesList) {
      $scope.gallery = $filter('filter')(GalleriesList, {
        id: parseInt($stateParams.id,10)
      })[0];
      $scope.$parent.selectedGallery = $scope.gallery;

      $scope.save = function() {
        Gallery.update($scope.gallery, function(data) {
          $scope.$emit('nope.toast.success', 'Gallery updated.');
          $scope.gallery = data;
          $scope.$parent.selectedGallery = $scope.gallery;
        });
      }
    }])
    /**
     * Services
     */
    .service('Gallery', ['$resource', function($resource) {
      return $resource('content/gallery/:id', {
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
