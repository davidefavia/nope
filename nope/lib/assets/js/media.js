(function() {
  'use strict';
  angular.module('nope.app')
  .config(['$stateProvider', function($stateProvider) {
    $stateProvider
    .state('app.media', {
      url : 'media',
      views : {
        'content@app' : {
          templateUrl : 'view/media/list.html',
          controller: 'MediaListController'
        }
      }
    })
    .state('app.media.detail', {
      url : '/view/:id',
      views : {
        'content@app.content' : {
          templateUrl : 'view/media/detail.html',
          controller : 'MediaDetailController'
        }
      }
    })
    ;
  }])
  /**
   * Controller
   */
  .controller('MediaListController', ['$scope', '$state', '$stateParams', '$nopeModal', 'Media', function($scope, $state, $stateParams, $nopeModal, Media) {
    $scope.contentType = 'media';

    $scope.deleteContentOnClick = function() {
      Media.delete({
        id:$scope.contentToDelete.id
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
      });
    }

    $scope.getAllContents();

  }])
  .controller('MediaDetailController', ['$scope', '$state', '$stateParams', 'Media', function($scope, $state, $stateParams, Media) {

  }])
  /**
   * Services
   */
  .service('Media', ['$resource', function($resource) {
    return $resource('content/media/:id', {id:'@id'}, {
      getAll : {
        isArray : true
      },
      update: {
        method: 'PUT'
      }
    });
  }])
  ;
})();
