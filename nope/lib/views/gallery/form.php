<div class="tmpl-topbar">
  <form name="galleryForm" ng-submit="save();">
    <nav class="navbar fixed-top navbar-light offset-md-3 offset-sm-3 offset-lg-2 topbar" nope-scroll="{amount:20,cssClass:'add-bg'}">
      <div class="row">
        <div class="col-10">
          <div class="form-group" ng-class="{'has-error':(!galleryForm.title.$valid && galleryForm.title.$touched)}">
            <input type="text" name="title" class="form-control form-control-lg" placeholder="Gallery title" ng-model="gallery.title" required />
          </div>
        </div>
        <div class="col-1">
          <button type="submit" class="btn btn-block btn-lg" ng-class="{'btn-outline-success':galleryForm.$invalid,'btn-success':galleryForm.$valid}" ng-disabled="galleryForm.$invalid" ng-class="{'btn-success':!galleryForm.$invalid}"><i class="fa fa-save"></i></button>
        </div>
        <div class="col-1">
          <a href="#/gallery" class="btn btn-block btn-danger btn-lg" ng-if="!gallery.id"><i class="fa fa-trash"></i></a>
          <a href="" class="btn btn-block btn-danger btn-lg" ng-if="gallery.id" nope-content-delete="deleteContentOnClick(gallery);" ng-model="gallery"><i class="fa fa-trash"></i></a>
        </div>
      </div>
    </nav>
    <div class="btn-group btn-group-xs toolbar pull-right" ng-if="gallery.id">
      <a ng-click="gallery.starred=!gallery.starred;" class="btn star"><i class="fa" ng-class="{'fa-star-o':!gallery.starred,'fa-star':gallery.starred}"></i></a>
    </div>
    <div class="form-group" ng-class="{'has-error':(!galleryForm.slug.$valid && galleryForm.slug.$touched)}">
      <label class="control-label">Slug</label>
      <input type="text" name="slug" class="form-control" ng-model="gallery.slug" required ng-pattern="<?php echo \Nope\Utils::SLUG_REGEX_PATTERN; ?>" ng-trim="false" placeholder="Gallery slug" />
    </div>
    <div class="form-group">
      <label class="control-label">Priority</label>
      <input type="text" name="slug" class="form-control" ng-model="gallery.priority" ng-pattern="/^[0-9]+$/" placeholder="Priority: higher first" />
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea name="body" class="form-control" ng-model="gallery.body" placeholder="Gallery description" rows="5"></textarea>
    </div>
    <div class="form-group">
      <label>Tags (comma separated)</label>
      <input type="text" name="tags" class="form-control" ng-model="gallery.tags" placeholder="Tags (comma separated)" />
    </div>
    <div class="form-group">
      <label>Cover</label>
      <nope-model href="#/media" ng-model="gallery.cover" multiple="false" label="Add gallery cover" preview="icon" template="media"></nope-model>
    </div>
    <div class="form-group">
      <label>Media</label>
      <nope-model href="#/media?excluded={{(gallery.media | nopeGetIds).join(',')}}" ng-model="gallery.media" label="Add media" preview="icon" template="media" multiple="true"></nope-model>
    </div>
    <?php
      $html = \Nope\View::renderCustomBox('gallery', 'gallery.custom.', NOPE_LIB_VIEWS_PATH . 'gallery/custom.php');
      if($html) {
        echo $html;
      }
    ?>
  </form>
</div>
