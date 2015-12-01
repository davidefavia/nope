<div id="media-detail">
  <form name="mediaForm" ng-submit="save()">
    <div class="preview" style="{{'background-image: url('+media.preview.thumb+');'}}" />
    </div>
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control" ng-model="media.title" required  />
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea name="description" class="form-control" ng-model="media.description"></textarea>
    </div>
    <div class="form-group">
      <label>Tags (comma separated)</label>
      <input type="text" name="tags" class="form-control" ng-model="media.tags" />
    </div>
    <div class="form-group clearfix">
      <div class="pull-right">
        <a href="" class="btn btn-warning" ng-if="changed" ng-click="reset();">Reset changes</a>
        <button class="btn" ng-disabled="mediaForm.$invalid" ng-class="{'btn-success':!mediaForm.$invalid}">Save</button>
      </div>
    </div>
  </form>
</div>
