(function() {
  'use strict';

  /* where to put? */
  Array.prototype.swapItems = function(a, b) {
    if (a >= 0 && b < this.length) {
      this[a] = this.splice(b, 1, this[a])[0];
    }
    return this;
  }
  Array.prototype.removeItemAt = function(index) {
    this.splice(index, 1);
    return this;
  }
  Array.prototype.swapCols = function(a, b) {
    for (i = 0; i < this.length; i++) {
      this[i].swapItems(a, b);
    }
    return this;
  }
  Array.prototype.addCol = function() {
    for (i = 0; i < this.length; i++) {
      this[i].push("");
    }
    return this;
  }
  Array.prototype.removeCol = function(index) {
    for (i = 0; i < this.length; i++) {
      this[i].splice(index, 1);
    }
    return this;
  }
  Array.prototype.addRow = function() {
    var l = this[0] ? this[0].length : 1;
    this.push(new Array(l));
    return this;
  }

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
    .service('$nopeToast', ['$rootScope', '$compile', function($rootScope, $compile) {

      var bodyElement = angular.element(document.body);
      bodyElement.append('<div id="notifications-container"></div>');
      var container = angular.element(document.getElementById('notifications-container'));

      function error(m,o) {
        show('danger',m,o || {});
      }

      function success(m,o) {
        show('success',m,o || {});
      }

      function warning(m,o) {
        show('warning',m,o || {});
      }

      function info(m,o) {
        show('info',m,o || {});
      }

      function buildToast(level, message, options) {
        options.timeout = options.timeout || 2500;
        return $compile('<div class="alert alert-'+level+'" nope-timeout="'+options.timeout+'">'+message+'</div>')($rootScope);
      }

      function show(level, message, options) {
        container.prepend(buildToast(level, message, options));
      }

      return {
        info : info,
        error : error,
        success : success,
        warning : warning
      }
    }])
    .provider('$nopeModal', [function() {

      var modal;

      function show() {
        angular.element(document.body).append(modal);
        modal.css({
          opacity: 1,
          display: 'block',
          background: 'rgba(0,0,0,.75)'
        });
      }

      function hide() {
        modal.remove();
      }

      this.$get = ['$compile', '$http', '$q', function($compile, $http, $q) {
        return {
          fromTemplate: function(templateString, scope) {
            modal = $compile(templateString)(scope);
            return $q.resolve({
              show: show,
              hide: hide
            });
          },
          fromTemplateUrl: function(templateUrl, scope) {
            return $http({
              url: templateUrl
            }).then(function(response) {
              modal = $compile(response.data)(scope);
              return $q.resolve({
                show: show,
                hide: hide
              });
            })
          }
        }
      }];

    }])
    /**
     * Directives
     */
    .directive('noEmpty', [function() {
      return {
        restrict: 'E',
        transclude: true,
        replace: true,
        template: '<div class="empty"><i class="fa fa-{{icon}}"><h3 ng-transclude></h3></div>',
        scope: {
          icon: '@'
        }
      }
    }])
    .directive('nopeModal', [function() {
      return {
        restrict: 'E',
        replace: true,
        transclude: true,
        template: '<div class="modal" nope-modal-close>\
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
        scope: {
          title: '@'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          this.close = function() {
            $element.remove();
          }
        }]
      }
    }])
    .directive('nopeModalBody', [function() {
      return {
        restrict: 'E',
        replace: true,
        transclude: true,
        template: '<div class="modal-body" ng-transclude></div>'
      }
    }])
    .directive('nopeModalFooter', [function() {
      return {
        restrict: 'E',
        replace: true,
        transclude: true,
        template: '<div class="modal-footer" ng-transclude></div>'
      }
    }])
    .directive('nopeModalClose', [function() {
      return {
        restrict: 'A',
        require: '^nopeModal',
        link: function($scope, $element, $attrs, nopeModalCtrl) {
          $element.on('click', function() {
            nopeModalCtrl.close();
          });
        }
      }
    }])
    .directive('nopeRole', ['$rootScope', function($rootScope) {
      return {
        restrict: 'A',
        scope: {
          role: '@nopeRole'
        },
        link: function($scope, $element, $attrs) {
          var roles = $scope.role.split(',');
          if (roles.indexOf($rootScope.currentUser.role) === -1) {
            $element.remove();
          }
        }
      }
    }])
    .directive('nopeCan', ['$rootScope', function($rootScope) {
      return {
        restrict: 'A',
        scope: {
          permission: '@nopeCan'
        },
        link: function($scope, $element, $attrs) {
          if (!$rootScope.currentUser.can($scope.permission)) {
            $element.remove();
          }
        }
      }
    }])
    .directive('nopeUpload', ['$compile', '$q', 'Upload', function($compile, $q, Upload) {
      return {
        restrict: 'A',
        terminal: true,
        priority: 1000,
        scope: {
          onDone: '&nopeUpload'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $scope.uploadFiles = function(files) {
            var promises = [];
            angular.forEach(files, function(file, i) {
              var q = Upload.upload({
                url: 'content/media/upload',
                data: {
                  file: file
                }
              });
              promises.push(q);
            });
            $q.all(promises).then(function() {
              $scope.onDone();
            });
          }
        }],
        link: function($scope, $element, $attrs) {
          $element.attr('ngf-select', 'uploadFiles($files)');
          $element.attr('multiple', 'multiple');
          $element.removeAttr('nope-upload');
          $compile($element)($scope);
        }
      }
    }])
    .directive('nopeZoom', ['$nopeModal', function($nopeModal) {
      return {
        restrict: 'A',
        scope: {
          path: '=nopeZoom'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $element.addClass('nope-zoom');
          $element.on('click', function(e) {
            e.preventDefault();
            $nopeModal.fromTemplate('<nope-modal class="zoom">\
          <nope-modal-body><img class="img-responsive" ng-src="{{path}}" /></nope-modal-body>\
          </nope-modal>', $scope).then(function(modal) {
              modal.show();
            })
          })
        }]
      }
    }])
    .directive('nopeModel', ['$injector', '$nopeModal', function($injector, $nopeModal) {
      return {
        restrict: 'E',
        replace: true,
        require: 'ngModel',
        template: '<div><div class="list-group" ng-show="ngModel">\
        <div class="list-group-item" ng-if="multiple" ng-repeat="item in ngModel track by $index">\
          <img class="img-thumbnail preview" ng-src="{{item.preview[preview]}}" ng-if="hasPreview" />\
          {{item.title}}\
          <div class="btn-group btn-group-xs pull-right">\
            <a href="" class="btn btn-default" ng-click="ngModel.swapItems($index, $index-1);" ng-if="!$first"><i class="fa fa-arrow-up"></i></a>\
            <a href="" class="btn btn-default" ng-click="ngModel.swapItems($index, $index+1);" ng-if="!$last"><i class="fa fa-arrow-down"></i></a>\
            <a href="" class="btn btn-danger" ng-click="ngModel.removeItemAt($index);"><i class="fa fa-times-circle"></i></a>\
          </div>\
        </div>\
        <div class="list-group-item" ng-if="!multiple">\
          <img class="img-thumbnail preview" ng-src="{{ngModel.preview[preview]}}" ng-if="hasPreview" />\
          {{ngModel.title}}\
          <a href="" class="btn btn-danger btn-xs pull-right" ng-click="remove();"><i class="fa fa-times-circle"></i></a>\
        </div>\
      </div>\
      <a href="" class="btn btn-block btn-default" ng-click="openModal()" ng-hide="!multiple && ngModel">Add {{model}} <i class="fa fa-plus"></i></a></div>',
        scope: {
          model: '@',
          method: '@',
          multiple: '=',
          ngModel: '=',
          title: '=',
          preview: '@'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          var model = $injector.get($scope.model);

          $scope.titleField = $scope.title || 'title';
          $scope.multiple = (angular.isDefined($scope.multiple) ? $scope.multiple : true);
          $scope.hasPreview = !!$scope.preview;
          var methodToInvoke = $scope.method || 'getAll';

          $scope.openModal = function() {
            model[methodToInvoke](function(data) {
              $scope.itemsList = data;
              $nopeModal.fromTemplateUrl('view/modal/model.html', $scope).then(function(modal) {
                modal.show();
              })
            });
          }

          $scope.onSelect = function(item) {
            if ($scope.multiple) {
              if (!angular.isArray($scope.ngModel)) {
                $scope.ngModel = [];
              }
              $scope.ngModel.push(item);
            } else {
              $scope.ngModel = item;
            }
          }

          $scope.remove = function() {
            $scope.ngModel = null;
          }

        }]
      }
    }])
    .directive('nopeTimeout', ['$timeout', function($timeout) {
      return {
        restrict : 'A',
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          var t = parseInt($attrs.nopeTimeout, 10);
          t = t || 1000;
          $timeout(function() {
            $element.remove();
          }, t);
        }]
      }
    }])
    .directive('nopeMatch', [function() {
      return {
        restrict : 'A',
        require : '^ngModel',
        scope : {
          nopeMatch : '=',
          ngModel : '='
        },
        link : function($scope, $element, $attrs, ngModelCtrl) {
          $scope.$watch('ngModel', function(n,o) {
            ngModelCtrl.$setValidity('match',(n===$scope.nopeMatch));
          }, true);
          $scope.$watch('nopeMatch', function(n,o) {
            ngModelCtrl.$setValidity('match',($scope.ngModel===n));
          }, true);
        }
      }
    }])
    ;
})()
