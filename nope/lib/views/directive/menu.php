<div><ul dnd-list="ngModel" class="list-group list-group-menu is-multiple">
  <!--<li class="dndPlaceholder fake" ng-if="!ngModel.length && show">Drop menus items here</li>-->
  <li class="list-group-item" ng-repeat="item in ngModel track by $index" dnd-draggable="item" dnd-moved="ngModel.splice($index,1)">
    <i class="fa fa-bars handle"></i>
    <div>
      <div>
        <div class="form-group">
          <div class="row">
            <div class="col col-md-4">
              <label>Label</label>
              <input type="text" class="form-control input-sm" ng-model="item.label" placeholder="Menu item label" />
            </div>
            <div class="col col-md-8">
              <label>URL</label>
              <input type="text" class="form-control input-sm" ng-model="item.value" placeholder="Relative to '<?php echo \Nope\Utils::getFullBaseUrl(); ?>' or absolute URL" />
            </div>
          </div>
        </div>
        <div class="form-group">
          <a href="" class="btn btn-link btn-xs" ng-click="showProperties=!showProperties;"><span ng-show="showProperties">Hide</span><span ng-show="!showProperties">Show</span> properties</a>
          <div class="row" ng-show="showProperties">
            <div class="col col-md-6">
              <label>Id</label>
              <input type="text" class="form-control input-sm" ng-model="item.id" placeholder="CSS item id" />
            </div>
            <div class="col col-md-6">
              <label>Target</label>
              <input type="text" class="form-control input-sm" ng-model="item.target" placeholder="Link target" />
              <a href="" ng-click="item.target='_self'" class="btn btn-link">_self</a>,
              <a href="" ng-click="item.target='_blank'" class="btn btn-link">_blank</a>,
              <a href="" ng-click="item.target='_parent'" class="btn btn-link">_parent</a>,
              <a href="" ng-click="item.target='_top'" class="btn btn-link">_top</a>
            </div>
          </div>
        </div>
        <div class="form-group">
          <nope-menu ng-model="item.items" show="true"></nope-menu>
        </div>
      </div>
      <div dnd-nodrag class="btn-group btn-group-xs toolbar">
        <a href="" class="btn" ng-click="ngModel.swapItems($index, $index-1);" ng-if="!$first"><i class="fa fa-arrow-up"></i></a>
        <a href="" class="btn" ng-click="ngModel.swapItems($index, $index+1);" ng-if="!$last"><i class="fa fa-arrow-down"></i></a>
        <a href="" class="btn text-danger" ng-click="ngModel.removeItemAt($index);"><i class="fa fa-times-circle"></i></a>
      </div>
    </div>
  </li>
</ul>
<a href="" ng-click="ngModel.push({items:[]});" class="btn btn-default btn-block">Add menu item <i class="fa fa-plus"></i></a></div>
