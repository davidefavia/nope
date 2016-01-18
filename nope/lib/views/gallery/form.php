<form name="galleryForm" ng-submit="save()" class="content-detail">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="toolbar">
        <a ng-click="gallery.starred=!gallery.starred;" class="btn btn-star"><i class="fa" ng-class="{'fa-star-o':!gallery.starred,'fa-star':gallery.starred}"></i></a>
      </div>
      <div class="form-group" ng-class="{'has-error':(!galleryForm.title.$valid && galleryForm.title.$touched)}">
        <label class="control-label">Title</label>
        <input type="text" name="title" class="form-control" ng-model="gallery.title" required />
      </div>
      <div class="form-group" ng-class="{'has-error':(!galleryForm.slug.$valid && galleryForm.slug.$touched)}">
        <label class="control-label">Slug</label>
        <input type="text" name="slug" class="form-control" ng-model="gallery.slug" required ng-pattern="<?php echo \Nope\Utils::SLUG_REGEX_PATTERN; ?>" ng-trim="false" />
      </div>
      <div class="form-group">
        <label>Body</label>
        <textarea name="body" class="form-control" ng-model="gallery.body"></textarea>
      </div>
      <div class="form-group">
        <label>Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" ng-model="gallery.tags" /></textarea>
      </div>
      <div class="form-group">
        <label>Cover</label>
        <nope-model href="#/media" ng-model="gallery.cover" multiple="false" label="Add cover" preview="icon"></nope-model>
      </div>
      <div class="form-group">
        <label>Media</label>
        <nope-model href="#/media?excluded={{(gallery.media | nopeGetIds).join(',')}}" ng-model="gallery.media" label="Add media" preview="icon"></nope-model>
      </div>
    </div>
    <div class="panel-footer">
      <div class="form-group clearfix">
        <div class="pull-right">
          <button class="btn" ng-disabled="galleryForm.$invalid" ng-class="{'btn-success':!galleryForm.$invalid}">Save</button>
        </div>
      </div>
    </div>
  </div>
</form>
