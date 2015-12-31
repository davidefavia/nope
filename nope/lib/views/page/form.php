<div class="page-form">
  <form name="contentForm" ng-submit="save()">
    <div class="row">
      <div class="col col-md-9">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="form-group">
              <label>Title</label>
              <input type="text" name="title" class="form-control input-lg" ng-model="content.title" required />
            </div>
            <div class="form-group">
              <label>Body</label>
              <textarea name="body" class="form-control" ng-model="content.body"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="col col-md-3">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="form-group" ng-if="content.author">
              <label>Author</label>
              <p class="form-control-static">{{content.author.prettyName || content.author.username}}</p>
            </div>
            <div class="form-group">
              <label>Slug</label>
              <input type="text" name="slug" class="form-control" ng-model="content.slug" required ng-pattern="/^[a-zA-Z0-9-_\/]+$/" ng-trim="false" />
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" ng-model="content.status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
              </select>
            </div>
            <div class="form-group">
              <label>Start publishing date</label>
              <input type="text" name="title" class="form-control" ng-model="content.startPublishingDate" placeholder="yyyy-mm-dd hh:mm:ss" />
            </div>
            <div class="form-group">
              <label>End publishing date</label>
              <input type="text" name="title" class="form-control" ng-model="content.endPublishingDate" placeholder="yyyy-mm-dd hh:mm:ss" />
            </div>
            <div class="form-group">
              <label>Cover</label>
              <nope-model model="Media" ng-model="content.cover" preview="icon" multiple="false"></nope-model>
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
