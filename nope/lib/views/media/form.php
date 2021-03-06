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
          <a href="" class="btn" ng-click="$parent.rotate(media,90);" ng-if="media.isImage"><i class="fa fa-rotate-left"></i></a>
          <a href="" class="btn" ng-click="$parent.rotate(media,-90);" ng-if="media.isImage"><i class="fa fa-rotate-right"></i></a>
          <a href="" nope-zoom="media.url" class="btn" ng-if="media.isImage"><i class="fa fa-arrows-alt"></i></a>
        </div>
        <span class="provider"ng-if="media.provider" ><i class="fa {{'fa-'+(media.provider | lowercase)}}" ></i> {{media.provider}}</span>
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
        <input type="text" name="title" class="form-control" ng-model="media.title" required placeholder="Media title" />
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="body" class="form-control" ng-model="media.body" rows="5" placeholder="Media description" ></textarea>
      </div>
      <div class="form-group">
        <label>Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" ng-model="media.tags" placeholder="Tags (comma separated)" />
      </div>
    </div>
    <div class="panel-footer">
      <button class="btn btn-block" ng-disabled="mediaForm.$invalid" ng-class="{'btn-success':!mediaForm.$invalid}">Save</button>
    </div>
  </div>
</form>
