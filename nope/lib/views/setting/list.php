<div class="row">
  <div class="list-column col col-md-4 col-sm-6">
    <div class="searchbar">
        <input type="text" class="form-control" ng-model="q" placeholder="Filter" />
    </div>
    <div class="list-group">
      <div class="list-group-item ng-cloak" ng-show="!filteredSettingsList.length && q">No setting found with filter "{{q}}".</div>
      <div class="list-group-item" ng-class="{active:p.settingkey===selectedSetting.settingkey}" ng-repeat="p in filteredSettingsList = (settingsList | filter:q)" ng-show="filteredSettingsList.length">
        <a ng-href="#/setting/view/{{p.settingkey}}"><h4 class="list-group-item-heading">{{::p.properties.label}}</h4></a>
        <p class="list-group-item-text" ng-if="p.properties.description">{{::p.properties.description}}</p>
      </div>
    </div>
  </div>
  <div class="col" ng-class="{'col-md-8 col-sm-6':settingsList.length}" ui-view="content">
    <no-empty icon="gears">
      <span>Select setting</span>
    </no-empty>
  </div>
</div>
