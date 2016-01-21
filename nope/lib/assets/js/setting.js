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
          url: '/view/:settingkey',
          views: {
            'content': {
              templateUrl: function($stateParams) {
                return 'view/setting/detail/'+$stateParams.settingkey+'.html'
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
        settingkey: $stateParams.settingkey
      }, function(data) {
        $scope.setting = data;
        $scope.$parent.selectedSetting = $scope.setting;
      });

      $scope.save = function() {
        Setting.update($scope.setting, function(data) {
          $scope.$emit('nope.toast.success', 'Setting updated.');
          $scope.setting = data;
          $scope.$parent.selectedSetting = $scope.setting;
        });
      }

    }])
    /**
     * Services
     */
    .service('Setting', ['$resource', function($resource) {
      return $resource('setting/:settingkey', {
        settingkey: '@settingkey'
      }, {
        update: {
          method: 'PUT'
        }
      });
    }]);
})();
