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
            return 'view/' + $stateParams.contentType + '/list.html'
          },
          controller: 'ContentsListController'
        }
      },
      resolve : {
        ContentsList : function($stateParams, Content) {
          return Content.getAll({
            type : $stateParams.contentType
          });
        }
      }
    })
    .state('app.content.create', {
      url : '/create',
      views : {
        'content@app' : {
          templateUrl : function($stateParams) {
            return 'view/' + $stateParams.contentType + '/form.html'
          },
          controller : 'ContentCreateController'
        }
      }
    })
    .state('app.content.edit', {
      url : '/edit/:id',
      views : {
        'content@app' : {
          templateUrl : function($stateParams) {
            return 'view/' + $stateParams.contentType + '/form.html'
          },
          controller : 'ContentEditController'
        }
      }
    })
    .state('app.content.detail', {
      url : '/:id',
      views : {
        'content@app.content' : {
          templateUrl : function($stateParams) {
            return 'view/' + $stateParams.contentType + '/detail.html'
          },
          controller : 'ContentDetailController'
        }
      }
    })
    ;
  }])
  /**
   * Controller
   */
  .controller('ContentsListController', ['$scope', '$stateParams', 'ContentsList', function($scope, $stateParams, ContentsList) {
    $scope.contentType = $stateParams.contentType;
    $scope.contentsList = ContentsList;
  }])
  .controller('ContentCreateController', ['$scope', '$state', '$stateParams', 'Content', function($scope, $state, $stateParams, Content) {
    $scope.content = new Content();

    $scope.save = function() {
      Content.save({
        type : $stateParams.contentType
      }, $scope.content, function(data) {
        $state.go('app.content.edit', {
          type : $stateParams.contentType,
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
  .controller('ContentDetailController', ['$scope', function($scope) {

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
