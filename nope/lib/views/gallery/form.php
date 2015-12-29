<form name="galleryForm" ng-submit="save()" class="content-detail">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="form-group">
        <input type="text" name="title" class="form-control" ng-model="gallery.title" required />
      </div>
    </div>
    <div class="panel-body">
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" ng-model="gallery.description"></textarea>
      </div>
      <div class="form-group">
        <label>Cover</label>
        <nope-model model="Media" ng-model="gallery.cover" preview="icon" multiple="false"></nope-model>
      </div>
      <div class="form-group">
        <label>Media</label>
        <nope-model model="Media" ng-model="gallery.media" preview="icon"></nope-model>
      </div>
    </div>
    <div class="panel-footer">
      <div class="form-group clearfix">
        <div class="pull-right">
          <a href="" class="btn btn-warning" ng-if="changed" ng-click="reset();">Reset changes</a>
          <button class="btn" ng-disabled="galleryForm.$invalid" ng-class="{'btn-success':!galleryForm.$invalid}">Save</button>
        </div>
      </div>
    </div>
  </div>
</form>
