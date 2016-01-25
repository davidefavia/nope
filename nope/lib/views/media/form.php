<form name="mediaForm" ng-submit="$parent.save(media)">
  <div class="panel panel-default">
    <div class="panel-heading content-author">
      <nope-author content="media" class="pull-left"></nope-author>
      <div class="btn-group btn-group-xs toolbar pull-right">
        <a href="" class="btn text-danger" nope-content-delete="$parent.deleteContentOnClick(media);" ng-model="media"><i class="fa fa-trash"></i></a>
      </div>
    </div>
    <div class="panel-body">
      <div class="media-preview" style="{{'background-image: url('+media.preview.thumb+');'}}">
        <div class="btn-group btn-group-xs toolbar pull-right">
          <a ng-click="media.starred=!media.starred;" class="btn star"><i class="fa" ng-class="{'fa-star-o':!media.starred,'fa-star':media.starred}"></i></a>
          <a href="" nope-image-edit="media" class="btn"><i class="fa fa-paint-brush"></i></a>
          <a href="" nope-zoom="media.url" class="btn" ng-if="media.isImage"><i class="fa fa-arrows-alt"></i></a>
        </div>
        <i class="provider fa {{'fa-'+(media.provider | lowercase)}}" ng-if="media.provider"></i>
      </div>
      <div class="form-group">
        <label>URL</label>
        <div class="input-group input-group-sm">
          <input type="text" class="form-control" ng-model="media.absoluteUrl" readonly nope-selectable />
          <span class="input-group-btn">
            <a ng-href="{{media.absoluteUrl}}" target="_blank" class="btn btn-default"><i class="fa fa-external-link"></i></a>
          <span>
        </div>
      </div>
      <div class="form-group" ng-class="{'has-error':(!mediaForm.title.$valid && mediaForm.title.$touched)}">
        <label class="control-label">Title</label>
        <input type="text" name="title" class="form-control" ng-model="media.title" required />
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="body" class="form-control" ng-model="media.body"></textarea>
      </div>
      <div class="form-group">
        <label>Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" ng-model="media.tags" />
      </div>
    </div>
    <div class="panel-footer">
      <button class="btn btn-block" ng-disabled="mediaForm.$invalid" ng-class="{'btn-success':!mediaForm.$invalid}">Save</button>
    </div>
  </div>
</form>
