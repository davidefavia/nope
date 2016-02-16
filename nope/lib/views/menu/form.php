<form name="menuForm" ng-submit="save(menu);">
  <div class="panel panel-default">
    <div class="panel-heading content-author" ng-if="menu.id">
      <div class="btn-group btn-group-xs toolbar pull-right">
        <a href="" nope-content-delete="$parent.deleteContentOnClick(menu);" ng-model="menu" class="btn text-danger"><i class="fa fa-trash"></i></a>
      </div>
      <nope-author content="menu" class="pull-left"></nope-author>
    </div>
    <div class="panel-body">
      <div class="form-group" ng-class="{'has-error':(!menuForm.title.$valid && menuForm.title.$touched)}">
        <input type="text" name="title" class="form-control input-lg" placeholder="Menu title" ng-model="menu.title" required />
      </div>
      <div class="form-group" ng-class="{'has-error':(!menuForm.slug.$valid && menuForm.slug.$touched)}">
        <label class="control-label">Slug</label>
        <input type="text" name="slug" class="form-control" ng-model="menu.slug" required ng-pattern="<?php echo \Nope\Utils::SLUG_REGEX_PATTERN; ?>" ng-trim="false" placeholder="Menu slug" />
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="body" class="form-control" ng-model="menu.body" placeholder="Menu description" rows="2"></textarea>
      </div>
      <div class="form-group">
        <label>Menu items</label>
        <nope-menu ng-model="menu.items"></nope-menu>
      </div>
    </div>
    <div class="panel-footer">
      <div class="form-group">
        <button class="btn btn-default btn-block" ng-disabled="menuForm.$invalid" ng-class="{'btn-success':!menuForm.$invalid}">Save</button>
      </div>
    </div>
  </div>
</form>
