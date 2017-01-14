(function() {
  'use strict';
  angular.module('nope.app')
    .config(['$stateProvider', function($stateProvider) {
      $stateProvider
        .state('app.gallery', {
          url: 'gallery',
          title: 'Gallery',
          views: {
            'content@app': {
              templateUrl: 'view/gallery/list.html',
              controller: 'GalleriesListController'
            }
          }
        })
        .state('app.gallery.create', {
          url: '/create',
          title: 'Gallery create',
          views: {
            'content@app': {
              templateUrl: 'view/gallery/form.html',
              controller: 'GalleryCreateController'
            }
          }
        })
        .state('app.gallery.detail', {
          url: '/view/{id:int}',
          title: 'Gallery detail',
          views: {
            'content@app': {
              templateUrl: 'view/gallery/form.html',
              controller: 'GalleryDetailController'
            }
          }
        });
    }])
    /**
     * Controller
     */
    .controller('GalleriesListController', ['$scope', '$rootScope', '$location', '$state', '$stateParams', '$nopeModal', '$nopeUtils', 'Gallery', function($scope, $rootScope, $location, $state, $stateParams, $nopeModal,  $nopeUtils, Gallery) {
      $scope.bulkSelection = [];
      $scope.contentsList = [];
      $scope.q = $location.search();

      $scope.deleteBulkContentOnClick = function() {
        var idsList = $scope.bulkSelection.map(function(item) {
          return item.id;
        });
        return Gallery.deleteList({
          id: idsList.join(',')
        }, function() {
          $scope.$emit('nope.toast.success', 'Galleries deleted.');
          $scope.search($scope.q);
        });
      }

      $scope.bulkEditTags = function() {
        $scope.bulkEditTagsOptions = {};
        $nopeModal.fromTemplateUrl('view/modal/tags.html', $scope).then(function(modal) {
          $scope.bulkEditTagsModal = modal;
          $scope.bulkEditTagsModal.show();
        });
      };

      $scope.bulkEditTagsAction = function(action, tags) {
        $scope.bulkEditTagsModal.hide();
        var idsList = $scope.bulkSelection.map(function(item) {
          return item.id;
        });
        return Gallery.editTagsList({}, {
          id: idsList.join(','),
          action: action,
          tags: tags
        }, function() {
          $scope.$emit('nope.toast.success', 'Gallery tags edited.');
          $scope.search($scope.q);
        });
      }

      $scope.openDetail = function(p) {
        $scope.bulkSelection = [];
        $state.go('app.gallery.detail', {
          id: p.id
        }, {
          inherit: true
        }).then(function(){
          $location.search($scope.q);
        });
      }

      $scope.search = function(q, page) {
        page = page || 1;
        $location.search(q);
        if(page===1) {
          $scope.bulkSelection = [];
        }
        Gallery.query(angular.extend({
          page : page
        }, q), function(data, headers) {
          $scope.metadata = angular.fromJson(headers().link);
          $scope.contentsList = (page===1?[]:$scope.contentsList).concat(data);
        });
      }

      $scope.search($scope.q);

      $scope.deleteContentOnClick = function(p) {
        var title = p.title;
        return Gallery.delete({
          id: p.id
        }, function() {
          $scope.$emit('nope.toast.success', 'Gallery "' + title + '" deleted.');
          $state.go('app.gallery', {}, {
            reload: true
          });
        });
      }

      $scope.deleteBulkContentOnClick = function() {
        var idsList = $scope.bulkSelection.map(function(item) {
          return item.id;
        });
        return Gallery.deleteList({
          id: idsList.join(',')
        }, function() {
          $scope.$emit('nope.toast.success', 'Galleries deleted.');
          $scope.search($scope.q);
        });
      }

      $scope.save = function(p, i) {
        Gallery.update(p, function(data) {
          $scope.$emit('nope.toast.success', 'Gallery "' + data.title + '" updated.');
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
          $scope.$broadcast('nope.gallery.updated', data);
        });
      }

    }])
    .controller('GalleryCreateController', ['$scope', '$state', 'Gallery', function($scope, $state, Gallery) {
      $scope.gallery = new Gallery();

      $scope.save = function() {
        Gallery.save($scope.gallery, function(data) {
          $scope.$emit('nope.toast.success', 'Gallery "'+data.title+'" created.');
          $state.go('app.gallery.detail', {
            id: data.id
          }, {
            reload: true
          });
        });
      }

      $scope.deleteContentOnClick = function(g) {
        var title = g.title;
        Gallery.delete(g, function(data) {
          $scope.$emit('nope.toast.success', 'Gallery "'+title+'" created.');
          $state.go('app.gallery');
        });
      }
    }])
    .controller('GalleryDetailController', ['$scope', '$filter', '$state', '$stateParams', 'Gallery', function($scope, $filter, $state, $stateParams, Gallery) {

      Gallery.get({
        id: $stateParams.id
      }, function(data) {
        $scope.gallery = data;
      });

      $scope.$on('nope.gallery.updated', function(e, data) {
        if(data.id === $stateParams.id) {
          $scope.gallery = data;
          $scope.$parent.$parent.selectedGallery = $scope.gallery;
        }
      });

      $scope.save = function() {
        Gallery.update($scope.gallery, function(data) {
          $scope.$emit('nope.toast.success', 'Gallery "'+data.title+'" updated.');
          $state.go('app.gallery.detail', {
            id: data.id
          }, {
            reload: true
          });
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
        },
        deleteList: {
          method: 'DELETE',
          url: 'content/gallery/list'
        },
        editTagsList: {
          method: 'POST',
          url: 'content/gallery/tags'
        }
      });
    }]);
})();
