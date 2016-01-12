<div class="page-form">
  <form name="contentForm" ng-submit="save()">
    <div class="row">
      <div class="col col-md-9">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="form-group" ng-class="{'has-error':(!contentForm.title.$valid && contentForm.title.$touched)}">
              <label class="control-label">Title</label>
              <input type="text" name="title" class="form-control input-lg" ng-model="content.title" required />
            </div>
            <div class="form-group">
              <label>Body</label>
              <textarea name="body" class="form-control" ng-model="content.body" rows="20"></textarea>
            </div>
            <div class="form-group">
              <label>Tags</label>
              <input type="text" name="tags" class="form-control" ng-model="content.tags" />
            </div>
          </div>
        </div>
      </div>
      <div class="col col-md-3">
        <div class="panel panel-default">
          <div class="panel-heading content-author">
            <div class="list-group">
              <div class="list-group-item clearfix">
                <img ng-src="{{content.author.cover.preview.icon}}" class="img-circle" />
                Created by <span class="fullname">{{content.author.prettyName || content.author.username}}</span>
                <span ng-if="content.id">{{content.creationDate | nopeMoment:'fromNow'}}</span>
                <span ng-if="!content.id">now</span>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="form-group content-featured-image">
              <div class="preview-image" ng-show="content.cover" ng-style="{backgroundImage:'url('+content.cover.preview.thumb+')'}">
                <a href="" ng-click="content.cover=null" class="btn btn-danger btn-xs"><i class="fa fa-times-circle"></i></a>
              </div>
              <nope-model model="Media" ng-model="content.cover" multiple="false" preview="icon" label="Add featured image"></nope-model>
            </div>
            <div class="form-group" ng-class="{'has-error':(!contentForm.slug.$valid && contentForm.slug.$touched)}">
              <label class="control-label">Slug</label>
              <input type="text" name="slug" class="form-control input-sm" ng-model="content.slug" required ng-pattern="/^[a-zA-Z0-9-_\/]+$/" ng-trim="false" />
            </div>
            <div class="form-group" ng-class="{'has-error':(!contentForm.format.$valid && contentForm.format.$touched)}">
              <label class="control-label">Format</label>
              <select class="form-control input-sm" ng-model="content.format" required>
                <option ng-repeat="t in textFormats" value="{{t.key}}">{{t.label}}</option>
              </select>
            </div>
            <div class="form-group content-status" ng-class="{'has-error':(!contentForm.status.$valid && contentForm.status.$touched)}">
              <label class="control-label">Status</label>
              <nope-publishing ng-model="content"></nope-publishing>
              <select class="form-control input-sm" ng-model="content.status" required ng-blur="getRealStatus()">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
              </select>
            </div>
            <div class="form-group">
              <label>Start publishing date</label>
              <input type="text" name="startPublishingDate" class="form-control input-sm" ng-model="content.startPublishingDate" placeholder="yyyy-mm-dd hh:mm:ss" ng-blur="getRealStatus()" />
            </div>
            <div class="form-group">
              <label>End publishing date</label>
              <input type="text" name="endPublishingDate" class="form-control input-sm" ng-model="content.endPublishingDate" placeholder="yyyy-mm-dd hh:mm:ss" ng-blur="getRealStatus()" />
            </div>
            <div class="form-group">
              <label>Summary</label>
              <textarea name="summary" class="form-control" ng-model="content.summary" rows="4"></textarea>
            </div>
            <div class="form-group">
              <button class="btn btn-block" ng-disabled="contentForm.$invalid" ng-class="{'btn-success':!contentForm.$invalid}">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
