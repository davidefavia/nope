<?php

switch($templateName) {
  default :
  case 'content':
    $template = '<div class="media-left" ng-if="%2$s.cover.preview[preview]">
      <img %1$s class="media-object img-thumbnail" ng-src="{{%2$s.cover.preview[preview]}}" />
      </div>
      <div class="media-body">
        <h4 class="media-heading">{{%2$s.title}}</h4>
        <h6>Id: {{%2$s.id}}</h6>
        <h6 ng-if="%2$s.realStatus"><nope-publishing ng-model="%2$s"></nope-publishing></h6>
        <h6 ng-if="%2$s.slug">Slug: {{%2$s.slug}}</h6>
      </div>';
    break;
  case 'gallery':
    $template = '<div class="media-left" ng-if="%2$s.cover.preview[preview]">
      <img %1$s class="media-object img-thumbnail" ng-src="{{%2$s.cover.preview[preview]}}" />
      </div>
      <div class="media-body">
        <h4 class="media-heading">{{%2$s.title}}</h4>
        <h6>Id: {{%2$s.id}}</h6>
        <h6>Media: {{%2$s.media.length}}</h6>
        <h6 ng-if="%2$s.slug">Slug: {{%2$s.slug}}</h6>
      </div>';
    break;
  case 'media' :
    $template = '<div class="media-left" ng-if="%2$s.preview[preview]">
      <img %1$s class="media-object img-thumbnail" ng-src="{{%2$s.preview[preview]}}" />
      </div>
      <div class="media-body">
        <h4 class="media-heading">{{%2$s.title}}</h4>
        <h6>Id: {{%2$s.id}}</h6>
        <h6 ng-if="!%2$s.isExternal">Mimetype: {{%2$s.mimetype}}</h6>
        <h6 ng-if="%2$s.isExternal">Type: {{%2$s.type}}</h6>
        <h6 ng-if="%2$s.isExternal">Provider: {{%2$s.provider}}</h6>
      </div>';
    break;
  case 'user':
    $template = '<div class="media-left" ng-if="%2$s.cover.preview[preview]">
      <img %1$s class="media-object img-thumbnail" ng-src="{{%2$s.cover.preview[preview]}}" />
      </div>
      <div class="media-body">
        <h4 class="media-heading">{{%2$s.prettyName || %2$s.username}}</h4>
        <h6>Member since: {{%2$s.creationDate | nopeMoment : \'format\' : \'LL\'}}</h6>
        <h6>Role: {{%2$s.role}}</h6>
      </div>';
    break;
}

$templateSingle = sprintf($template, '', 'ngModel');
$templateList = sprintf($template, 'dnd-nodrag', 'item');

?>
<div>
  <ul dnd-list="ngModel" class="list-group list-group-contents is-multiple" ng-show="ngModel && preview" ng-if="multiple">
    <li class="list-group-item media" ng-repeat="item in ngModel track by $index" dnd-draggable="item" dnd-moved="ngModel.splice($index,1)">
      <i class="fa fa-bars handle"></i>
      <?php echo $templateList; ?>
      <div dnd-nodrag class="btn-group btn-group-xs toolbar">
        <a href="" class="btn" ng-click="ngModel.swapItems($index, $index-1);" ng-if="!$first"><i class="fa fa-arrow-up"></i></a>
        <a href="" class="btn" ng-click="ngModel.swapItems($index, $index+1);" ng-if="!$last"><i class="fa fa-arrow-down"></i></a>
        <a href="" class="btn text-danger" ng-click="ngModel.removeItemAt($index);"><i class="fa fa-times-circle"></i></a>
      </div>
    </li>
  </ul>
  <ul class="list-group list-group-contents" ng-show="ngModel && preview" ng-if="!multiple">
    <li class="list-group-item media">
      <?php echo $templateSingle; ?>
      <div class="btn-group btn-group-xs toolbar pull-right">
        <a href="" class="btn text-danger" ng-click="remove();"><i class="fa fa-times-circle"></i></a>
      </div>
    </li>
  </ul>
  <a href="" class="btn btn-block btn-default" ng-click="openModal($event)" ng-hide="!multiple && ngModel">{{label || 'Add'}} <i class="fa fa-plus"></i></a>
</div>
