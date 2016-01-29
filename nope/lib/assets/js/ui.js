(function() {
  'use strict';

  /* where to put? */
  Array.prototype.hasItem = function(a) {
    return this.itemIndex(a) !== -1;
  }
  Array.prototype.itemIndex = function(a) {
    return this.indexOf(a);
  }
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
    for (var i = 0; i < this.length; i++) {
      this[i].swapItems(a, b);
    }
    return this;
  }
  Array.prototype.addCol = function() {
    for (var i = 0; i < this.length; i++) {
      this[i].push("");
    }
    return this;
  }
  Array.prototype.removeCol = function(index) {
    for (var i = 0; i < this.length; i++) {
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
        input = input ? input.split(' ').join('T') + 'Z' : input;
        return $filter('date')(input, format, timezone);
      }
    }])
    .filter('nopeMoment', [function() {
      return function(input, format, timezone) {
        input = input ? input.split(' ').join('T') + 'Z' : input;
        format = format || 'fromNow';
        return moment(input, 'YYYY-MM-DD hh:mm:ss')[format]();
      }
    }])
    .filter('nopeGetIds', [function() {
      return function(input) {
        var ids = []
        angular.forEach(input, function(v, index) {
          ids.push(v.id);
        });
        return ids;
      }
    }])
    /**
     * Services
     */
    .service('$nopeUtils', ['$window', function($window) {

      var getContentModalCallerScope = function() {
        var $parent = $window.parent;
        var el = $parent.angular.element($parent.document.getElementById('modal-content'));
        return el.isolateScope().$parent;
      }

      return {
        getContentModalCallerScope : getContentModalCallerScope
      }
    }])
    .service('$nopeToast', ['$rootScope', '$compile', function($rootScope, $compile) {

      var bodyElement = angular.element(document.body);
      bodyElement.append('<div id="notifications-container"></div>');
      var container = angular.element(document.getElementById('notifications-container'));

      var error = function(m,o) {
        show('danger',m,o || {});
      }

      var success = function(m,o) {
        show('success',m,o || {});
      }

      var warning = function(m,o) {
        show('warning',m,o || {});
      }

      var info = function(m,o) {
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
        template: '<div class="modal" ng-click="close($event);">\
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

          $scope.close = function($event) {
            if(angular.element($event.target).hasClass('modal')) {
              $element.remove();
            }
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
    .directive('nopeModalClose', ['$compile', function($compile) {
      return {
        restrict: 'A',
        require: '^nopeModal',
        link: function($scope, $element, $attrs, nopeModalCtrl) {
          $element.on('click', function($event) {
            $event.stopPropagation();
            $event.cancelBubble = true;
            $event.preventDefault();
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
    .directive('nopeUploadModal', ['$nopeModal', function($nopeModal) {
      return {
        restrict: 'A',
        scope: {
          onUploadDone: '&nopeUploadModal',
          accept : '@'
        },
        link: function($scope, $element, $attrs) {
          var theModal;
          $element.addClass('nope-upload-modal');
          $element.on('click', function(e) {
            e.preventDefault();
            $nopeModal.fromTemplateUrl('view/modal/upload.html', $scope).then(function(modal) {
              theModal = modal;
              theModal.show();
            });
          });

          $scope.onDone = function() {
            theModal.hide();
            $scope.onUploadDone();
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
          onDone: '&nopeUpload',
          accept : '@'
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
          if($scope.accept) {
            $element.attr('ngf-accept', $scope.accept.toString());
          }
          $element.attr('multiple', 'multiple');
          $element.removeAttr('nope-upload');
          $compile($element)($scope);
        }
      }
    }])
    .directive('nopeImport', ['Media', function(Media) {
      return {
        restrict: 'E',
        template: '<form name="importForm" ng-submit="importMedia();">\
          <div class="form-group">\
            <label>or import from url:</label>\
            <div class="input-group">\
              <input type="url" class="form-control" ng-model="importUrl" required placeholder="Insert URL" >\
              <div class="input-group-btn">\
                <button class="btn btn-default" ng-disabled="importForm.$invalid">Import</button>\
              </div>\
            </div>\
          </div>\
        </form>',
        replace: true,
        scope: {
          onDone: '&onDone'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $scope.importMedia = function() {
            Media.import({
              url : $scope.importUrl
            }, function() {
              $scope.onDone();
            });
          }
        }]
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
          });
        }]
      }
    }])
    .directive('nopeModel', ['$injector', '$nopeModal', 'BasePath', function($injector, $nopeModal, BasePath) {
      return {
        restrict: 'E',
        replace: true,
        require: 'ngModel',
        template: '<div>\
          <ul dnd-list="ngModel" class="list-group list-group-contents is-multiple" ng-show="ngModel && preview" ng-if="multiple">\
            <li class="list-group-item" ng-repeat="item in ngModel" dnd-draggable="item" dnd-moved="ngModel.splice($index,1)">\
              <i class="fa fa-bars handle"></i>\
              <img dnd-nodrag class="img-thumbnail preview" ng-src="{{item.preview[preview]}}" ng-if="hasPreview" />\
              <span dnd-nodrag class="title">{{item.title}}</span>\
              <div dnd-nodrag class="btn-group btn-group-xs toolbar">\
                <a href="" class="btn" ng-click="ngModel.swapItems($index, $index-1);" ng-if="!$first"><i class="fa fa-arrow-up"></i></a>\
                <a href="" class="btn" ng-click="ngModel.swapItems($index, $index+1);" ng-if="!$last"><i class="fa fa-arrow-down"></i></a>\
                <a href="" class="btn text-danger" ng-click="ngModel.removeItemAt($index);"><i class="fa fa-times-circle"></i></a>\
              </div>\
            </li>\
          </ul>\
          <ul class="list-group list-group-contents" ng-show="ngModel && preview" ng-if="!multiple">\
            <li class="list-group-item">\
              <img class="img-thumbnail preview" ng-src="{{ngModel.preview[preview]}}" ng-if="hasPreview" />\
              <span class="title">{{ngModel.title}}</span>\
              <div class="btn-group btn-group-xs toolbar pull-right">\
                <a href="" class="btn text-danger" ng-click="remove();"><i class="fa fa-times-circle"></i></a>\
              </div>\
            </li>\
          </ul>\
          <a href="" class="btn btn-block btn-default" ng-click="openModal($event)" ng-hide="!multiple && ngModel">{{label || \'Add\'}} <i class="fa fa-plus"></i></a>\
        </div>',
        scope: {
          multiple: '=?',
          ngModel: '=',
          title: '=?',
          preview: '@?',
          label: '@?',
          url: '@?href'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          var theModal;

          $scope.titleField = $scope.title || 'title';
          $scope.multiple = (angular.isDefined($scope.multiple) ? $scope.multiple : true);
          $scope.hasPreview = !!$scope.preview;

          $scope.openModal = function($event) {
            $scope.selection = [];
            $scope.url = BasePath +'?iframe=1' + $scope.url;
            $nopeModal.fromTemplateUrl('view/modal/content.html', $scope).then(function(modal) {
              theModal = modal;
              theModal.show();
            });
          }

          $scope.$on('modal.hidden', function() {
            $scope.selection = [];
          });

          $scope.onSelect = function(items) {
            if ($scope.multiple) {
              if (!angular.isArray($scope.ngModel)) {
                $scope.ngModel = [];
              }
              $scope.ngModel = $scope.ngModel.concat(items);
            } else {
              $scope.ngModel = items[0];
            }
            theModal.hide();
          }

          $scope.selectedItem = function(c) {
            if($scope.selection.hasItem(c)) {
              $scope.selection.removeItemAt($scope.selection.itemIndex(c));
            } else {
              if(!$scope.multiple) {
                $scope.selection.removeItemAt(0);
              }
              $scope.selection.push(c);
            }
            return $scope.selection;
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
    .directive('nopePublishing', [function() {
      return {
        restrict : 'E',
        replace : true,
        scope: {
          ngModel: '='
        },
        template : function($element, $attrs) {
          var m = 'ngModel';//$attrs.ngModel;
          var html = [];
          html.push('<span class="nope-publishing">');
          html.push('<span class="label label-info" ng-if="'+m+'.realStatus==\'draft-published\'">Draft ready to be published</span>');
          html.push('<span ng-if="'+m+'.realStatus==\'draft-expired\'"><span class="label label-danger">Draft already expired</span> {{'+m+'.endPublishingDate | nopeMoment}}</span>');
          html.push('<span ng-if="'+m+'.realStatus==\'draft-scheduled\'"><span class="label label-danger">Draft scheduled</span> {{'+m+'.startPublishingDate | nopeMoment}}</span>');
          html.push('<span ng-if="'+m+'.realStatus==\'published\'"><span class="label label-success">Published</span> {{'+m+'.startPublishingDate | nopeMoment}}</span>');
          html.push('<span ng-if="'+m+'.realStatus==\'expired\'"><span class="label label-danger">Expired</span> {{'+m+'.endPublishingDate | nopeMoment}}</span>');
          html.push('<span ng-if="'+m+'.realStatus==\'scheduled\'"><span class="label label-warning">Scheduled</span> {{'+m+'.startPublishingDate | nopeMoment}}</span>');
          html.push('</span>');
          return html.join('');
        }
      }
    }])
    .directive('nopeSelectable', [function() {
      return {
        restrict : 'A',
        controller : ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $element.on('focus', function(e) {
            $element[0].select();
          });
        }]
      }
    }])
    .directive('nopeAuthor', [function() {
      return {
        restrict : 'E',
        replace: true,
        templateUrl : 'view/directive/author.html',
        scope : {
          content : '='
        }
      }
    }])
    .directive('nopeContentDelete', ['$nopeModal', function($nopeModal) {
      return {
        restrict : 'A',
        require : 'ngModel',
        scope : {
          ngModel : '=',
          deleteContentOnClick : '&nopeContentDelete'
        },
        controller : ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $element.on('click', function() {
            $nopeModal.fromTemplateUrl('view/modal/content-delete.html', $scope).then(function(modal) {
              $scope.theModal = modal;
              $scope.theModal.show();
            });
          });

          $scope.deleteContent = function() {
            $scope.deleteContentOnClick($scope.ngModel).$promise.then(function() {
              $scope.theModal.hide();
            });
          }
        }]
      }
    }])
    .directive('nopeUserDelete', ['$nopeModal', function($nopeModal) {
      return {
        restrict : 'A',
        require : 'ngModel',
        scope : {
          ngModel : '=',
          deleteUserOnClick : '&nopeUserDelete'
        },
        controller : ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $element.on('click', function() {
            $nopeModal.fromTemplateUrl('view/modal/user-delete.html', $scope).then(function(modal) {
              $scope.theModal = modal;
              $scope.theModal.show();
            });
          });

          $scope.deleteUser = function() {
            $scope.deleteUserOnClick($scope.ngModel).$promise.then(function() {
              $scope.theModal.hide();
            });
          }
        }]
      }
    }])
    ;
})()
