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
  .controller('ContentsListController', ['$scope', '$state', '$stateParams', '$nopeModal', 'Content', function($scope, $state, $stateParams, $nopeModal, Content) {
    $scope.contentType = $stateParams.contentType;
    $scope.contentsList = [];

    $scope.searchByText = function(text, page) {
      page = page || 1;
      if(page===1) {
        $scope.contentsList = [];
      }
      Content.query({
        type : $stateParams.contentType,
        page : page,
        query : text
      }, function(data, headers) {
        $scope.metadata = angular.fromJson(headers().link);
        $scope.contentsList = $scope.contentsList.concat(data);
      });
    }

    $scope.searchByText($scope.q);

    $scope.deleteContentOnClick = function() {
      Content.delete({
        type : $stateParams.contentType,
        id:$scope.contentToDelete.id
      }, function() {
        $scope.$emit('nope.toast.success', 'Content deleted.');
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
  .controller('ContentCreateController', ['$scope', '$rootScope', '$state', '$stateParams', 'Content', function($scope, $rootScope, $state, $stateParams, Content) {
    $scope.content = new Content();
    $scope.content.author = $rootScope.currentUser;

    $scope.save = function() {
      Content.save({
        type : $stateParams.contentType
      }, $scope.content, function(data) {
        $scope.$emit('nope.toast.success', 'Content created.');
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
        $scope.$emit('nope.toast.success', 'Content updated.');
        $scope.content = data;
      });
    }

    $scope.getRealStatus = function() {
      if(!$scope.content.id) {
        return;
      }
      Content.getCalculatedStatus({
        type : $stateParams.contentType,
        id : $scope.content.id,
        status : $scope.content.status,
        startPublishingDate : $scope.content.startPublishingDate,
        endPublishingDate : $scope.content.endPublishingDate
      }, function(data) {
        $scope.content.realStatus = data.realStatus;
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
      },
      getCalculatedStatus : {
        url : 'content/:type/:id/status'
      }
    });
  }])
  ;
})();
