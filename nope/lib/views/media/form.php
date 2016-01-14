<form name="mediaForm" ng-submit="save()" id="media-detail" class="content-detail">
  <div class="panel panel-default">
    <div class="panel-heading content-author">
      <div class="list-group">
        <div class="list-group-item clearfix" ng-class="{'has-image':media.author.cover}">
          <img ng-src="{{media.author.cover.preview.icon}}" class="img-circle" ng-if="media.author.cover" />
          Created by <span class="fullname">{{media.author.prettyName || media.author.username}}</span>
          <span>{{media.creationDate | nopeMoment:'fromNow'}}</span>
        </div>
      </div>
      <div class="preview" style="{{'background-image: url('+media.preview.thumb+');'}}">
        <div class="toolbar">
          <a ng-click="media.starred=!media.starred;" class="btn btn-star btn-lg pull-right"><i class="fa" ng-class="{'fa-star-o':!media.starred,'fa-star':media.starred}"></i></a>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div class="form-group">
        <label>URL</label>
        <div class="input-group input-group-sm">
          <input type="text" class="form-control" ng-model="media.absoluteUrl" readonly nope-selectable />
          <span class="input-group-btn">
            <a ng-href="{{media.absoluteUrl}}" target="_blank" class="btn btn-default"><i class="fa fa-external-link"></i></a>
          <span>
        </div>

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
    </div>
    <div class="panel-footer">
      <div class="form-group clearfix">
        <div class="pull-right">
          <a href="" class="btn btn-warning" ng-if="changed" ng-click="reset();">Reset changes</a>
          <button class="btn" ng-disabled="mediaForm.$invalid" ng-class="{'btn-success':!mediaForm.$invalid}">Save</button>
        </div>
      </div>
    </div>
  </div>
</form>
