(function() {
  'use strict';
  angular.module('nope.app')
    .config(['$stateProvider', function($stateProvider) {
      $stateProvider
        .state('app.setting', {
          url: 'setting',
          views: {
            'content@app': {
              templateUrl: 'view/setting/list.html',
              controller: 'SettingsListController'
            }
          }
        })
        .state('app.setting.detail', {
          url: '/view/:key',
          views: {
            'content': {
              templateUrl: function($stateParams) {
                return 'view/setting/detail/'+$stateParams.key+'.html'
              },
              controller: 'SettingDetailController'
            }
          }
        })
    }])
    /**
     * Controller
     */
    .controller('SettingsListController', ['$scope', '$nopeModal', 'Setting', function($scope, $nopeModal, Setting) {
      $scope.settingsList = [];
      Setting.query(function(data) {
        $scope.settingsList = data;
      });
    }])
    .controller('SettingDetailController', ['$scope', '$stateParams', 'Setting', function($scope, $stateParams, Setting) {

      Setting.get({
        key: $stateParams.key
      }, function(data) {
        $scope.setting = data;
        $scope.$parent.selectedSetting = $scope.setting;
      });

    }])
    /**
     * Services
     */
    .service('Setting', ['$resource', function($resource) {
      return $resource('setting/:key', {
        key: '@key'
      }, {
        update: {
          method: 'PUT'
        }
      });
    }]);
})();
