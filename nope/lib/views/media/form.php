<form name="mediaForm" ng-submit="$parent.save(media)">
  <div class="card card--media card--media-detail">
    <div class="card-header">
      <nope-author content="media" class="pull-left"></nope-author>
      <a href="" class="btn btn-sm btn-light text-muted" ng-click="$parent.closeDetail();"><i class="fa fa-close"></i></a>
    </div>
    <div class="card-image-block">
      <nope-lazy src="media.preview.thumb"></nope-lazy>
      <div class="btn-group btn-group-sm toolbar">
        <a href="" ng-click="media.starred=!media.starred;" class="btn text-white"><i class="fa" ng-class="{'fa-star-o':!media.starred,'fa-star':media.starred}"></i></a>
        <a href="" class="btn text-white" ng-click="$parent.rotate(media,90);" ng-if="media.isImage"><i class="fa fa-rotate-left"></i></a>
        <a href="" class="btn text-white" ng-click="$parent.rotate(media,-90);" ng-if="media.isImage"><i class="fa fa-rotate-right"></i></a>
        <a href="" nope-zoom="media" class="btn text-white" ng-if="media.isImage"><i class="fa fa-arrows-alt"></i></a>
        <a href="" class="btn text-danger" nope-content-delete="$parent.deleteContentOnClick(media);" ng-model="media"><i class="fa fa-trash"></i></a>
      </div>
      <h4 class="provider" ng-if="media.provider" ><i class="fa {{'fa-'+(media.provider | lowercase)}}" ></i> {{media.provider}}</h4>
    </div>
    <div class="card-block card-form">
      <div class="form-group">
        <label>URL <a ng-href="{{media.absoluteUrl}}" target="_blank"><i class="fa fa-external-link"></i></a></label>
        <input type="text" class="form-control" ng-model="media.absoluteUrl" readonly nope-selectable />
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
    <div class="card-footer">
      <button class="btn btn-block" ng-disabled="mediaForm.$invalid" ng-class="{'btn-success':!mediaForm.$invalid}">Save</button>
    </div>
  </div>
</form>
