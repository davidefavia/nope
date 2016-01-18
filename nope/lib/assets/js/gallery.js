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
            GalleriesList : ['$location', 'Gallery', function($location, Gallery) {
              return Gallery.query($location.search()).$promise;
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
        });
    }])
    /**
     * Controller
     */
    .controller('GalleriesListController', ['$scope', '$rootScope', '$location', '$state', '$stateParams', '$nopeModal', '$nopeUtils', 'Gallery', 'GalleriesList', function($scope, $rootScope, $location, $state, $stateParams, $nopeModal,  $nopeUtils, Gallery, GalleriesList) {
      $scope.contentType = 'gallery';
      $scope.selectedGallery = null;
      $scope.selectedGalleryIndex = null;
      $scope.contentsList = GalleriesList;

      if($location.search()) {
        $scope.q = $location.search();
      }

      $scope.selection = [];

      $scope.select = function(c,i) {
        if($rootScope.nope.isIframe) {
          var callerScope = $nopeUtils.getContentModalCallerScope();
          $scope.selection = callerScope.selectedItem(c);
          callerScope.$apply();
        } else {
          $scope.selectedMediaIndex = i;
          $state.go('app.gallery.detail', {id:c.id});
        }
      }

      $scope.deleteContentOnClick = function() {
        var title = $scope.contentToDelete.title;
        Gallery.delete({
          id: $scope.contentToDelete.id
        }, function() {
          $scope.$emit('nope.toast.success', 'Gallery "' + title + '" deleted.');
          $scope.theModal.hide();
          if ($scope.selectedGallery && $scope.selectedGallery.id === $scope.contentToDelete.id) {
            $scope.selectedGallery = null;
            $scope.selectedGalleryIndex = null;
            $state.go('app.gallery');
          }
          $scope.getAllContents();
        });
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
        Gallery.query(angular.extend({
          page : page
        }, q), function(data, headers) {
          $scope.metadata = angular.fromJson(headers().link);
          $scope.contentsList = $scope.contentsList.concat(data);
          GalleriesList = $scope.contentsList;
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
         $scope.theModal = modal;
         $scope.theModal.show();
        });
      };

      $scope.getAllContents = function() {
        Gallery.query(function(data) {
          $scope.contentsList = data;
        });
      }

    }])
    .controller('GalleryCreateController', ['$scope', '$state', 'Gallery', function($scope, $state, Gallery) {
      $scope.gallery = new Gallery();

      $scope.save = function() {
        Gallery.save($scope.gallery, function(data) {
          $scope.$emit('nope.toast.success', 'Gallery created.');
          $state.go('app.gallery.detail', {
            id: data.id
          }, {
            reload: true
          });
        });
      }
    }])
    .controller('GalleryDetailController', ['$scope', '$filter', '$state', '$stateParams', 'Gallery', 'GalleriesList', function($scope, $filter, $state, $stateParams, Gallery, GalleriesList) {
      $scope.gallery = $filter('filter')(GalleriesList, {
        id: parseInt($stateParams.id, 10)
      })[0];
      $scope.$parent.selectedGallery = $scope.gallery;

      $scope.save = function() {
        Gallery.update($scope.gallery, function(data) {
          $scope.$emit('nope.toast.success', 'Gallery "' + data.title + '" updated.');
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
        update: {
          method: 'PUT'
        }
      });
    }]);
})();
