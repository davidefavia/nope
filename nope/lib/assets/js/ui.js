(function() {
  'use strict';

  /* where to put? */
  Array.prototype.hasItem = function(a) {
    return this.itemIndex(a) !== -1;
  }
  Array.prototype.toggleItem = function(a) {
    if(this.hasItem(a)) {
      this.splice(this.itemIndex(a), 1);
    } else {
      this.push(a);
    }
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
     .filter('nopeBites', ['$filter', function($filter) {
       // http://stackoverflow.com/a/18650828
       return function(bytes) {
         var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
         if (bytes == 0) {
           return '0 Byte';
         }
         var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
         return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
       }
     }])
    .filter('nopeDate', ['$filter', function($filter) {
      return function(input, format, timezone) {
        input = input ? input.toString().split(' ').join('T') + 'Z' : input;
        format = format || 'yyyy-MM-dd HH:mm:ss';
        return $filter('date')(input, format, timezone);
      }
    }])
    .filter('nopeMoment', [function() {
      return function(input, format, t) {
        input = input ? input.split(' ').join('T') + 'Z' : input;
        format = format || 'fromNow';
        return moment(input, 'YYYY-MM-DD hh:mm:ss')[format](t);
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
        getContentModalCallerScope: getContentModalCallerScope
      }
    }])
    .service('$nopeToast', ['$rootScope', '$compile', function($rootScope, $compile) {

      var bodyElement = angular.element(document.body);
      bodyElement.append('<div id="notifications-container"></div>');
      var container = angular.element(document.getElementById('notifications-container'));

      var error = function(m, o) {
        show('danger', m, o || {});
      }

      var success = function(m, o) {
        show('success', m, o || {});
      }

      var warning = function(m, o) {
        show('warning', m, o || {});
      }

      var info = function(m, o) {
        show('info', m, o || {});
      }

      function buildToast(level, message, options) {
        options.timeout = options.timeout || 2500;
        return $compile('<div class="alert alert-' + level + '" nope-timeout="' + options.timeout + '">' + message + '</div>')($rootScope);
      }

      function show(level, message, options) {
        container.prepend(buildToast(level, message, options));
      }

      return {
        info: info,
        error: error,
        success: success,
        warning: warning
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
    // http://stackoverflow.com/a/18609594
    .factory('nopeRecursionHelper', ['$compile', function($compile) {
      return {
        /**
         * Manually compiles the element, fixing the recursion loop.
         * @param element
         * @param [link] A post-link function, or an object with function(s) registered via pre and post properties.
         * @returns An object containing the linking functions.
         */
        compile: function(element, link) {
          // Normalize the link parameter
          if (angular.isFunction(link)) {
            link = {
              post: link
            };
          }

          // Break the recursion loop by removing the contents
          var contents = element.contents().remove();
          var compiledContents;
          return {
            pre: (link && link.pre) ? link.pre : null,
            /**
             * Compiles and re-adds the contents
             */
            post: function(scope, element) {
              // Compile the contents
              if (!compiledContents) {
                compiledContents = $compile(contents);
              }
              // Re-add the compiled contents to the element
              compiledContents(scope, function(clone) {
                element.append(clone);
              });

              // Call the post-linking function, if any
              if (link && link.post) {
                link.post.apply(null, arguments);
              }
            }
          };
        }
      };
    }])
    .service('$nopeLoading', ['$rootScope', '$injector', '$window', function($rootScope, $injector, $window) {

      var $compile = $injector.get('$compile');
      var count = 0;
      $rootScope.nopeScreen = {
        width : document.body.clientWidth + 'px',
        height : document.body.clientHeight + 'px'
      }

      $window.onresize = function() {
        $rootScope.nopeScreen = {
          width : document.body.clientWidth + 'px',
          height : document.body.clientHeight + 'px'
        }
      };

      function show() {
        count++;
        if(!document.getElementById('loader')) {
          var loader = $compile('<div id="loader" style="width: {{nopeScreen.width}}; height:{{nopeScreen.height}};"><div><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div></div>')($rootScope);
          angular.element(document.body).append(loader);
        }
      }

      function hide() {
        if(count>0) {
          count--;
          if(count===0) {
            remove();
          }
        }
      }

      function remove() {
        count = 0;
        angular.element(document.getElementById('loader')).remove();
      }

      return {
        show: show,
        hide: hide,
        remove : remove
      }
    }])
    /**
     * Directives
     */
    .directive('nopeEmpty', [function() {
      return {
        restrict: 'E',
        transclude: true,
        replace: true,
        template: '<div class="empty"><i class="fa fa-{{icon}} fa-5x"></i><h3 ng-transclude></h3></div>',
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
            if (angular.element($event.target).hasClass('modal')) {
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
          accept: '@'
        },
        link: function($scope, $element, $attrs) {
          var theModal;
          $scope.progressList = {};
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
          accept: '@',
          onProgress: '='
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $scope.onProgress = {};
          $scope.uploadFiles = function(files) {
            var promises = [];
            angular.forEach(files, function(file, i) {
              var q = Upload.upload({
                url: 'content/media/upload',
                data: {
                  file: file
                }
              }).then(function (resp) {
                console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ', resp);
              }, function (resp) {
                console.log('Error status: ' + resp.status, file);
                $scope.onProgress[resp.config.data.file.name] = {
                  percentage: 0,
                  error: true,
                  errorMessage: (resp.data.exception.length?resp.data.exception[0].message:resp.statusText)
                };
              }, function (evt) {
                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.onProgress[evt.config.data.file.name] = {
                  percentage: progressPercentage,
                  error: false
                };
                console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
              });
              promises.push(q);
            });
            $q.all(promises).then(function() {
              $scope.onDone();
            }, function() {
              $scope.onDone();
            });
          }
        }],
        link: function($scope, $element, $attrs) {
          $element.attr('ngf-select', 'uploadFiles($files)');
          if ($scope.accept) {
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
              url: $scope.importUrl
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
          item: '=nopeZoom'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $element.addClass('nope-zoom');
          $element.on('click', function(e) {
            e.preventDefault();
            var pswpElement = document.querySelectorAll('.pswp')[0];
            // build items array
            var items = [
                {
                    src: $scope.item.url + '?__t__=' + (new Date()).getTime(),
                    w: $scope.item.width,
                    h: $scope.item.height
                }
            ];

            // define options (if needed)
            var options = {
                // optionName: 'option value'
                // for example:
                index: 0, // start at first slide,
                bgOpacity: .75
            };

            // Initializes and opens PhotoSwipe
            var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();
          });
        }]
      }
    }])
    .directive('nopeModel', ['$injector', '$nopeModal', 'BasePath', function($injector, $nopeModal, BasePath) {
      return {
        restrict: 'E',
        replace: true,
        require: 'ngModel',
        templateUrl: function($element, $attrs) {
          return 'view/directive/model/' + ($attrs.template || 'content') + '.html'
        },
        scope: {
          multiple: '=?',
          ngModel: '=',
          title: '@?',
          preview: '@?',
          label: '@?',
          url: '@?href',
          model: '@?',
          button : '=?',
          icon : '@'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          var theModal;

          $scope.preview = $scope.preview || 'icon';
          $scope.multiple = (angular.isDefined($scope.multiple) ? $scope.multiple : false);

          $scope.openModal = function($event) {
            $scope.selection = [];
            $scope.url = BasePath + '?iframe=1' + $scope.url;
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
            if ($scope.selection.hasItem(c)) {
              $scope.selection.removeItemAt($scope.selection.itemIndex(c));
            } else {
              if (!$scope.multiple) {
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
        restrict: 'A',
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          var t = parseInt($attrs.nopeTimeout, 10);
          t = t || 1000;
          $timeout(function() {
            $element.css({opacity:0});
            $timeout(function() {
              $element.remove();
            }, 250);
          }, t);
        }]
      }
    }])
    .directive('nopeMatch', [function() {
      return {
        restrict: 'A',
        require: '^ngModel',
        scope: {
          nopeMatch: '=',
          ngModel: '='
        },
        link: function($scope, $element, $attrs, ngModelCtrl) {
          $scope.$watch('ngModel', function(n, o) {
            if(n && $scope.nopeMatch) {
              ngModelCtrl.$setValidity('match', (n.toString() === $scope.nopeMatch.toString()));
            }
          }, true);
          $scope.$watch('nopeMatch', function(n, o) {
            if(n && $scope.ngModel) {
              ngModelCtrl.$setValidity('match', ($scope.ngModel.toString() === n.toString()));
            }
          }, true);
        }
      }
    }])
    .directive('nopePublishing', [function() {
      return {
        restrict: 'E',
        replace: true,
        scope: {
          ngModel: '='
        },
        template: function($element, $attrs) {
          var m = 'ngModel'; //$attrs.ngModel;
          var html = [];
          html.push('<span class="nope-publishing">');
          html.push('<span class="label label-info" ng-if="' + m + '.realStatus==\'draft-published\'">Draft ready to be published</span>');
          html.push('<span ng-if="' + m + '.realStatus==\'draft-expired\'"><span class="label label-danger">Draft already expired</span> {{' + m + '.endPublishingDate | nopeMoment}}</span>');
          html.push('<span ng-if="' + m + '.realStatus==\'draft-scheduled\'"><span class="label label-danger">Draft scheduled</span> {{' + m + '.startPublishingDate | nopeMoment}}</span>');
          html.push('<span ng-if="' + m + '.realStatus==\'published\'"><span class="label label-success">Published</span> {{' + m + '.startPublishingDate | nopeMoment}}</span>');
          html.push('<span ng-if="' + m + '.realStatus==\'expired\'"><span class="label label-danger">Expired</span> {{' + m + '.endPublishingDate | nopeMoment}}</span>');
          html.push('<span ng-if="' + m + '.realStatus==\'scheduled\'"><span class="label label-warning">Scheduled</span> {{' + m + '.startPublishingDate | nopeMoment}}</span>');
          html.push('</span>');
          return html.join('');
        }
      }
    }])
    .directive('nopeSelectable', [function() {
      return {
        restrict: 'A',
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $element.on('focus', function(e) {
            $element[0].select();
          });
        }]
      }
    }])
    .directive('nopeAuthor', [function() {
      return {
        restrict: 'E',
        replace: true,
        templateUrl: 'view/directive/author.html',
        scope: {
          content: '='
        }
      }
    }])
    .directive('nopeContentDelete', ['$nopeModal', function($nopeModal) {
      return {
        restrict: 'A',
        require: 'ngModel',
        scope: {
          ngModel: '=',
          deleteContentOnClick: '&nopeContentDelete'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
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
        restrict: 'A',
        require: 'ngModel',
        scope: {
          ngModel: '=',
          deleteUserOnClick: '&nopeUserDelete'
        },
        controller: ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
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
    .directive('nopeContentSelection', ['$rootScope', '$nopeUtils', function($rootScope, $nopeUtils) {
      return {
        restrict: 'A',
        require: 'ngModel',
        scope: {
          selection: '=ngModel',
          item: '=nopeContentSelection'
        },
        link: function($scope, $element, $attrs) {
          $scope.selection = angular.isArray($scope.selection) ? $scope.selection : [];

          $element.on('click', function(e) {
            if ($rootScope.nope.isIframe) {
              e.preventDefault();
              var callerScope = $nopeUtils.getContentModalCallerScope();
              $scope.selection = callerScope.selectedItem($scope.item);
              callerScope.$apply();
              $scope.$parent.$apply();
            }
          });
        }
      }
    }])
    .directive('nopeMenu', ['nopeRecursionHelper', function(nopeRecursionHelper) {
      return {
        restrict: 'E',
        require: '^ngModel',
        replace : true,
        scope: {
          ngModel: '=',
          show: '='
        },
        templateUrl: 'view/directive/menu.html',
        compile: function($element) {
          // Use the compile function from the RecursionHelper,
          // And return the linking function(s) which it returns
          return nopeRecursionHelper.compile($element);
        }
      }
    }])
    .directive('nopeDatetime', ['$filter', '$nopeModal', function($filter, $nopeModal) {
      return {
        restrict : 'AE',
        require : '^ngModel',
        scope : {
          theDate : '=ngModel',
          minDate : '@min',
          maxDate : '@max'
        },
        link: function($scope, $element, $attrs) {
          var theModal;
          var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

          $scope.days = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
          $scope.months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
          'August', 'September', 'October', 'November', 'December'];
          $scope.today = new Date();
          $scope.todayMonth = $scope.today.getMonth();
          $scope.todayYear = $scope.today.getFullYear();
          $scope.selectedDate = new Date();
          $scope.now = new Date();
          $scope.selectedHours = '00';
          $scope.selectedMinutes = '00';
          $scope.selectedSeconds = '00';

          $scope.selectDay = function(year, month, day) {
            $scope.selectedDate = new Date(year, month, day, $scope.selectedHours || 0, $scope.selectedMinutes || 0, $scope.selectedSeconds || 0);
            calculate($scope.todayYear, $scope.todayMonth);
          }

          $scope.selectNow = function() {
            $scope.selectedDate = new Date();
            $scope.selectedHours = ($scope.selectedDate.getHours()<10?'0'+$scope.selectedDate.getHours():$scope.selectedDate.getHours());
            $scope.selectedMinutes = ($scope.selectedDate.getMinutes()<10?'0'+$scope.selectedDate.getMinutes():$scope.selectedDate.getMinutes());
            $scope.selectedSeconds = ($scope.selectedDate.getSeconds()<10?'0'+$scope.selectedDate.getSeconds():$scope.selectedDate.getSeconds());
            $scope.todayMonth = $scope.today.getMonth();
            $scope.todayYear = $scope.today.getFullYear();
            calculate($scope.todayYear, $scope.todayMonth);
          }

          $scope.selectToday = function() {
            $scope.selectedDate = new Date();
            $scope.todayMonth = $scope.today.getMonth();
            $scope.todayYear = $scope.today.getFullYear();
            calculate($scope.todayYear, $scope.todayMonth);
          }

          $scope.$watchGroup(['selectedDate','selectedHours', 'selectedMinutes', 'selectedSeconds'], function(n,o) {
            $scope.canSelect = true;
            $scope.lowerStringLimit = 'any date';
            $scope.upperStringLimit = 'any date';
            if(n) {
              if($scope.lowerDateLimit === false && $scope.upperDateLimit === false) {
              } else {
                var lower = $scope.minDate ? $scope.minDate.split(' ')[0].split('-') : false;
                var lowerTime = $scope.minDate ? $scope.minDate.split(' ')[1].split(':') : false;
                var pMin = (lower!==false? (new Date(lower[0], parseInt(lower[1],10)-1, lower[2], lowerTime[0], lowerTime[1], lowerTime[2])): false);
                var upper = $scope.maxDate ? $scope.maxDate.split(' ')[0].split('-') : false;
                var upperTime = $scope.maxDate ? $scope.maxDate.split(' ')[1].split(':') : false;
                var pMax = (upper!==false? (new Date(upper[0], parseInt(upper[1],10)-1, upper[2], upperTime[0], upperTime[1], upperTime[2])): false);
                var d = n[0];
                d.setHours(parseInt(n[1],10));
                d.setMinutes(parseInt(n[2],10));
                d.setMinutes(parseInt(n[3],10));
                if(!pMin || (pMin && pMin.getTime()<=d.getTime())) {
                } else {
                  $scope.lowerStringLimit = pMin;
                  $scope.canSelect = false;
                }
                if(!pMax || (pMax && pMax.getTime()>=d.getTime())) {
                } else {
                  $scope.upperStringLimit = pMax;
                  $scope.canSelect = false;
                }
              }
            } else {
              $scope.canSelect = false;
            }
          }, true);

          $scope.select = function(d) {
            $scope.theDate = [
              [
                $scope.selectedDate.getFullYear(),
                ($scope.selectedDate.getMonth()<9?'0'+($scope.selectedDate.getMonth()+1):$scope.selectedDate.getMonth()+1),
                ($scope.selectedDate.getDate()<=9?'0'+($scope.selectedDate.getDate()):$scope.selectedDate.getDate())
              ].join('-'),
              [
                $scope.selectedHours,
                $scope.selectedMinutes,
                $scope.selectedSeconds
              ].join(':')
            ].join(' ');
            theModal.hide();
          }

          var calculate = function(year, month) {
            var daysThisMonth = daysInMonth[month];
            if(month===1 && ((year%4===0 && year%400!==0) || year%400===0) ) {
              daysThisMonth++;
            }
            var row = 0;
            var matrix = [];
            for(var i=1;i<=daysThisMonth; i++) {
              var d = new Date(year, month, i);
              var getDay = d.getDay() || 7;
              if(getDay%7===1) {
                row++;
              }
              if(!matrix[row]) {
                matrix[row] = [];
              }
              var enabled = (
                ($scope.lowerDateLimit!==false?$scope.lowerDateLimit<=d.getTime():true) && ($scope.upperDateLimit!==false?$scope.upperDateLimit>=d.getTime():true)
              );
              matrix[row][getDay-1] = {
                label : i,
                isToday : (year===$scope.today.getFullYear() && month===$scope.today.getMonth() && i===$scope.today.getDate()),
                isSelected : (year===$scope.selectedDate.getFullYear() && month===$scope.selectedDate.getMonth() && i===$scope.selectedDate.getDate()),
                isEnabled : enabled
              };
            }
            $scope.matrix = matrix;
            $scope.actualMonth = [$scope.months[month], year].join(' ');
          }

          $scope.previousMonth = function() {
            if($scope.todayMonth===0) {
              $scope.todayMonth = 11;
              $scope.todayYear--;
            } else {
              $scope.todayMonth--;
            }
            calculate($scope.todayYear, $scope.todayMonth);
          }

          $scope.nextMonth = function() {
            if($scope.todayMonth===11) {
              $scope.todayMonth = 0;
              $scope.todayYear++;
            } else {
              $scope.todayMonth++;
            }
            calculate($scope.todayYear, $scope.todayMonth);
          }

          $element.attr('readonly', 'readonly');
          $element.on('click', function(e) {
            e.preventDefault();
            if(!document.getElementById('modal-datetime')) {
              var lower = $scope.minDate ? $scope.minDate.split(' ')[0].split('-') : false;
              $scope.lowerDateLimit = (lower!==false? (new Date(lower[0], parseInt(lower[1],10)-1, lower[2], 0, 0, 0).getTime()): false);
              var upper = $scope.maxDate ? $scope.maxDate.split(' ')[0].split('-') : false;
              $scope.upperDateLimit = (upper!==false? (new Date(upper[0], parseInt(upper[1],10)-1, upper[2], 23, 59, 59).getTime()): false);
              $scope.todayVisible = (($scope.lowerDateLimit?$scope.today.getTime()>=$scope.lowerDateLimit:true) && ($scope.upperDateLimit?$scope.today.getTime()<=$scope.upperDateLimit:true));
              var w = $scope.$watch('theDate', function(n,o) {
                if(n) {
                  $scope.selectedDate = new Date($scope.theDate.split(' ').join('T'));
                  var p = $scope.theDate.split(' ')[1].split(':');
                  $scope.selectedHours = p[0];
                  $scope.selectedMinutes = p[1];
                  $scope.selectedSeconds = p[2];
                  w();
                }
                calculate($scope.todayYear, $scope.todayMonth);
              }, true);
              $nopeModal.fromTemplateUrl('view/modal/datetime.html', $scope).then(function(modal) {
                theModal = modal;
                theModal.show();
              });
            }
          });

        }
      }
    }])
    .directive('nopeToolbar', ['$rootScope', function($rootScope) {
      return {
        restrict : 'E',
        templateUrl : 'view/directive/toolbar.html',
        replace: true,
        scope : {
          selection : '=ngModel'
        },
        controller : ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {

          $scope.$watch('selection', function(n,o) {
            if(n!=={}) {
              var k = Object.keys(n);
              $rootScope.$broadcast('nope.editor.selection', k[0], n[k[0]]);
              $scope.selection = {};
            }
          }, true);

        }]
      }
    }])
    .directive('nopeEditor', ['$rootScope', '$compile', function($rootScope, $compile) {
      return {
        restrict : 'A',
        scope : {
          format : '=nopeEditor',
          ngModel : '='
        },
        link : function($scope, $element, $attrs) {
          var simplemde;
          $scope.valueToinsert = {};

          $rootScope.$on('nope.editor.selection', function(e, data, c) {
            var cm = simplemde.codemirror;
            var s = simplemde.getState();
            if(data === 'image') {
              _replaceSelection(cm, s.image, ["!["+c.title+"]({{uploadspath}}"+c.filename,")"]);
            } else if(data === 'page') {
              _replaceSelection(cm, s.link, ["[","]({{basepath}}"+c.slug+")"]);
            } else if(data === 'media') {
              _replaceSelection(cm, false, ["[n:media id=\""+c.id+"\"]","\n"]);
            } else if(data === 'gallery') {
              _replaceSelection(cm, false, ["[n:gallery slug=\""+c.slug+"\"]","\n"]);
            }
          });

          var _replaceSelection = function(cm, active, startEnd) {
            if(/editor-preview-active/.test(cm.getWrapperElement().lastChild.className)) {
              return;
            }
            var text;
            var start = startEnd[0];
            var end = startEnd[1];
            var startPoint = cm.getCursor("start");
            var endPoint = cm.getCursor("end");
            if(active) {
              text = cm.getLine(startPoint.line);
              start = text.slice(0, startPoint.ch);
              end = text.slice(startPoint.ch);
              cm.replaceRange(start + end, {
                line: startPoint.line,
                ch: 0
              });
            } else {
              text = cm.getSelection();
              cm.replaceSelection(start + text + end);

              startPoint.ch += start.length;
              if(startPoint !== endPoint) {
                endPoint.ch += start.length;
              }
            }
            cm.setSelection(startPoint, endPoint);
            cm.focus();
          }

          $scope.$watch('format', function(n, o) {
            if(n && n==='markdown') {
              simplemde = new SimpleMDE({
                autoDownloadFontAwesome : false,
                blockStyles : {
                  bold : '**',
                  italic : '_'
                },
                toolbar : [
                  'bold',
                  'italic',
                  'strikethrough',
                  'heading-1',
                  'heading-2',
                  'heading-3',
                  'code',
                  'quote',
                  'unordered-list',
                  'ordered-list',
                  'clean-block',
                  'link',
                  'image',
                  'table',
                  'horizontal-rule',
                  '|',
                  'fullscreen',
                  {
                    name: 'guide',
                    action: 'https://guides.github.com/features/mastering-markdown/#syntax',
                    className: 'fa fa-question-circle',
                    title: 'Markdown guide'
                  }
                ],
                element: $element[0],
                spellChecker : false,
                status: ['lines', 'words']
              });
              simplemde.value($scope.ngModel);
              var nopeToolbar = $compile('<nope-toolbar ng-model="valueToinsert"></nope-toolbar>')($scope);
              var toolbar = angular.element(document.getElementsByClassName('editor-toolbar')[0]);
              toolbar.append(nopeToolbar);
            } else {
              if(simplemde) {
                simplemde.codemirror.toTextArea();
                $element[0].style.display = 'block';
                var children = $element.parent().children();
                var found = false;
                angular.forEach(children, function(child) {
                  if(!found) {
                    found = (child===$element[0]);
                  } else {
                    child.remove();
                  }
                });
              }
            }
          });

          $scope.$watch(function() {
            if(simplemde) {
              return simplemde.value();
            }
            return;
          }, function(n, o) {
            $scope.ngModel = n;
          });
        }
      }
    }])
    .directive('nopeLazy', [function() {
      return {
        restrict : 'E',
        scope : {
          src : '='
        },
        template: '<div class="lazy" ng-class="{loaded:!loading}">\
          <i class="loading fa fa-circle-o-notch fa-spin"></i>\
          <div class="bg" style="background-image: url({{imageUrl}})"></div>\
        </div>',
        controller : ['$scope', '$element', '$attrs', function($scope, $element, $attrs) {
          $scope.$watch('src', function(n,o) {
            if(n) {
              _load($scope.src, false);
            }
          }, true);

          function _load(src, isDefault) {
            $scope.loading = true;
            var image = new Image();
            image.onload = function() {
              $scope.imageUrl = image.src;
              $scope.loading = false;
              $scope.$apply();
            }
            image.src = $scope.src;
          }

        }]
      }
    }])
    ;
})()
