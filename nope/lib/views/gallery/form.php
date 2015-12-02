<div id="gallery-detail">
  <form name="galleryForm" ng-submit="save()">
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control" ng-model="gallery.title" required />
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea name="description" class="form-control" ng-model="gallery.description"></textarea>
    </div>
    <div class="form-group">
      <label>Cover</label>
      <nope-model model="Media" ng-model="gallery.cover" preview="icon" multiple="false"></nope-model>
    </div>
    <div class="form-group clearfix">
      <div class="pull-right">
        <a href="" class="btn btn-warning" ng-if="changed" ng-click="reset();">Reset changes</a>
        <button class="btn" ng-disabled="galleryForm.$invalid" ng-class="{'btn-success':!galleryForm.$invalid}">Save</button>
      </div>
    </div>
  </form>
</div>
