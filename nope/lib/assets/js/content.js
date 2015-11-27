(function() {
  'use strict';
  angular.module('nope.app')
  .config(['$stateProvider', function($stateProvider) {
    $stateProvider
    .state('app.content', {
      url : 'content/:contentType',
      views : {
        'content@app' : {
          templateUrl : function($stateParams) {
            return 'view/' + $stateParams.contentType + '/list.html';
          },
          controller: 'ContentsListController'
        }
      },
      resolve : {
        ContentsList : ['$stateParams', 'Content', function($stateParams, Content) {
          return Content.getAll({
            type : $stateParams.contentType
          });
        }]
      }
    })
    .state('app.content.detail', {
      url : '/view/:id',
      views : {
        'content@app.content' : {
          templateUrl : function($stateParams) {
            return 'view/' + $stateParams.contentType + '/detail.html'
          },
          controller : 'ContentDetailController'
        }
      }
    })
    .state('app.contentcreate', {
      url : 'content/:contentType/create',
      views : {
        'content@app' : {
          templateUrl : function($stateParams) {
            return 'view/' + $stateParams.contentType + '/form.html'
          },
          controller : 'ContentCreateController'
        }
      }
    })
    .state('app.contentedit', {
      url : 'content/:contentType/edit/:id',
      views : {
        'content@app' : {
          templateUrl : function($stateParams) {
            return 'view/' + $stateParams.contentType + '/form.html'
          },
          controller : 'ContentEditController'
        }
      }
    })
    ;
  }])
  /**
   * Controller
   */
  .controller('ContentsListController', ['$scope', '$state', '$stateParams', '$nopeModal', 'Content', 'ContentsList', function($scope, $state, $stateParams, $nopeModal, Content, ContentsList) {
    $scope.contentType = $stateParams.contentType;
    $scope.contentsList = ContentsList;

    $scope.deleteContentOnClick = function() {
      Content.delete({
        type : $stateParams.contentType,
        id:$scope.contentToDelete.id
      }, function() {
        return;
        $state.go('app.content', {
          type : $stateParams.contentType
        }, {
          reload: true
        });
      });
    }

    $scope.deleteContent = function(c) {
      $scope.contentToDelete = c;
      $nopeModal.fromTemplate('<nope-modal title="Delete content">\
      <nope-modal-body><p>Are you sure to delete content "{{contentToDelete.title}}"?</p></nope-modal-body>\
      <nope-modal-footer>\
        <a class="btn btn-default" nope-modal-close>Close</a>\
        <a class="btn btn-danger" ng-click="deleteContentOnClick();">Yes, delete</a>\
      </nope-modal-footer>\
     </nope-modal>', $scope).then(function(modal) {
       modal.show();
     });
    };
  }])
  .controller('ContentCreateController', ['$scope', '$state', '$stateParams', 'Content', function($scope, $state, $stateParams, Content) {
    $scope.content = new Content();

    $scope.save = function() {
      Content.save({
        type : $stateParams.contentType
      }, $scope.content, function(data) {
        return;
        $state.go('app.contentedit', {
          contentType : $stateParams.contentType,
          id : data.id
        });
      });
    }
  }])
  .controller('ContentEditController', ['$scope', '$stateParams', 'Content', function($scope, $stateParams, Content) {
    Content.get({
      type : $stateParams.contentType,
      id : $stateParams.id
    }, function(data) {
      $scope.content = data;
    });

    $scope.save = function() {
      Content.update({
        type : $stateParams.contentType
      }, $scope.content, function(data) {
        $scope.content = data;
      });
    }

  }])
  .controller('ContentDetailController', ['$scope', '$stateParams', 'Content', function($scope, $stateParams, Content) {
    Content.get({
      type : $stateParams.contentType,
      id : $stateParams.id
    }, function(data) {
      $scope.content = data;
      $scope.$parent.selectedContent = $scope.content;
    });
  }])
  /**
   * Services
   */
  .service('Content', ['$resource', function($resource) {
    return $resource('content/:type/:id', {type:'@type', id:'@id'}, {
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
