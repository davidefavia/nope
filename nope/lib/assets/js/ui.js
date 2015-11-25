(function() {
  angular.module('nope.ui', [])
  /**
   * Filters
   */
  .filter('nopeDate', ['$filter', function($filter) {
    return function(input, format, timezone) {
      input = input.split(' ').join('T') + 'Z';
      return $filter('date')(input, format, timezone);
    }
  }])
  /**
   * Services
   */
  .provider('$nopeModal', [function() {

    var modal;

    function show() {
      angular.element(document.body).append(modal);
      modal.css({
        opacity: 1,
        display: 'block',
        background : 'rgba(0,0,0,.3)'
      });
    }

    function hide() {
      modal.remove();
    }

    this.$get = ['$compile', '$http', '$q', function($compile, $http, $q) {
      return {
        fromTemplate : function(templateString, scope) {
          modal = $compile(templateString)(scope);
          return $q.resolve({
            show : show,
            hide : hide
          });
        },
        fromTemplateUrl : function(templateUrl, scope) {
          return $http({url: templateUrl}).then(function(response) {
            modal = $compile(response.data)(scope);
            return $q.resolve({
              show : show,
              hide : hide
            });
          })
        }
      }
    }];

  }])
  /**
   * Directives
   */
  .directive('nopeModal', [function() {
    return {
      restrict : 'E',
      replace: true,
      transclude : true,
      template : '<div class="modal" nope-modal-close>\
       <div class="modal-dialog">\
         <div class="modal-content">\
           <div class="modal-header" ng-if="title">\
             <a class="close" nope-modal-close><span>&times;</span></a>\
             <h4 class="modal-title">{{title}}</h4>\
           </div>\
           <div ng-transclude></div>\
         </div>\
       </div>\
      </div>',
      scope : {
        title : '@'
      },
      controller : function($scope, $element, $attrs) {
        this.close = function() {
          $element.remove();
        }
      }
    }
  }])
  .directive('nopeModalBody', [function() {
    return {
      restrict : 'E',
      replace : true,
      transclude : true,
      template : '<div class="modal-body" ng-transclude></div>'
    }
  }])
  .directive('nopeModalFooter', [function() {
    return {
      restrict : 'E',
      replace : true,
      transclude : true,
      template : '<div class="modal-footer" ng-transclude></div>'
    }
  }])
  .directive('nopeModalClose', [function() {
    return {
      restrict : 'A',
      require : '^nopeModal',
      link : function($scope, $element, $attrs, nopeModalCtrl) {
        $element.on('click', function() {
          nopeModalCtrl.close();
        });
      }
    }
  }])
  .directive('nopeRole', ['$rootScope', function($rootScope) {
    return {
      restrict : 'A',
      scope : {
        role : '@nopeRole'
      },
      link : function($scope, $element, $attrs) {
        var roles = $scope.role.split(',');
        if(roles.indexOf($rootScope.currentUser.role)===-1) {
          $element.remove();
        }
      }
    }
  }])
  .directive('nopeCan', ['$rootScope', function($rootScope) {
    return {
      restrict : 'A',
      scope : {
        permission : '@nopeCan'
      },
      link : function($scope, $element, $attrs) {
        if(!$rootScope.currentUser.can($scope.permission)) {
          $element.remove();
        }
      }
    }
  }])
})()
