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
      },
      resolve : {
        MediaList : function(Content) {
          return Content.getAll({
            type : 'media'
          });
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
  .controller('MediaListController', ['$scope', '$state', '$stateParams', 'Upload', '$nopeModal', 'Content', 'MediaList', function($scope, $state, $stateParams, Upload, $nopeModal, Content, MediaList) {
    $scope.contentsList = MediaList;

    $scope.deleteContentOnClick = function() {
      Content.delete({
        type : 'media',
        id:$scope.contentToDelete.id
      }, function() {
        $state.go('app.media', null, {
          reload: true
        });
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

    $scope.uploadFiles = function(files) {
      angular.forEach(files, function(file, i) {
        Upload.upload({
          url : 'content/media/upload',
          data : {file: file}
        }).then(function(data) {
          MediaList.push(data);
        });
      });
    }

  }])
  ;
})();
