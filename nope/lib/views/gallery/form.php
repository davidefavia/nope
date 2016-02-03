<form name="galleryForm" ng-submit="save();">
  <div class="panel panel-default">
    <div class="panel-heading content-author" ng-if="gallery.id">
      <div class="btn-group btn-group-xs toolbar pull-right">
        <a ng-click="gallery.starred=!gallery.starred;" class="btn star"><i class="fa" ng-class="{'fa-star-o':!gallery.starred,'fa-star':gallery.starred}"></i></a>
        <a href="" nope-content-delete="$parent.deleteContentOnClick(gallery);" ng-model="gallery" class="btn text-danger"><i class="fa fa-trash"></i></a>
      </div>
      <nope-author content="gallery" class="pull-left"></nope-author>
    </div>
    <div class="panel-body">
      <div class="form-group" ng-class="{'has-error':(!galleryForm.title.$valid && galleryForm.title.$touched)}">
        <input type="text" name="title" class="form-control input-lg" placeholder="Gallery title" ng-model="gallery.title" required />
      </div>
      <div class="row">
        <div class="col col-md-9">
          <div class="form-group" ng-class="{'has-error':(!galleryForm.slug.$valid && galleryForm.slug.$touched)}">
            <label class="control-label">Slug</label>
            <input type="text" name="slug" class="form-control" ng-model="gallery.slug" required ng-pattern="<?php echo \Nope\Utils::SLUG_REGEX_PATTERN; ?>" ng-trim="false" />
          </div>
        </div>
        <div class="col col-md-3">
          <div class="form-group">
            <label class="control-label">Priority</label>
            <input type="text" name="slug" class="form-control" ng-model="gallery.priority" ng-pattern="/^[0-9]+$/" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label>Body</label>
        <textarea name="body" class="form-control" ng-model="gallery.body"></textarea>
      </div>
      <div class="form-group">
        <label>Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" ng-model="gallery.tags" />
      </div>
      <div class="form-group">
        <label>Cover</label>
        <nope-model href="#/media" ng-model="gallery.cover" multiple="false" label="Add gallery cover" preview="icon"></nope-model>
      </div>
      <div class="form-group">
        <label>Media</label>
        <nope-model href="#/media?excluded={{(gallery.media | nopeGetIds).join(',')}}" ng-model="gallery.media" label="Add media" preview="icon"></nope-model>
      </div>
    </div>
    <div class="panel-footer">
      <div class="form-group">
        <button class="btn btn-default btn-block" ng-disabled="galleryForm.$invalid" ng-class="{'btn-success':!galleryForm.$invalid}">Save</button>
      </div>
    </div>
  </div>
</form>
