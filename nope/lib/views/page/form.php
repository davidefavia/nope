<div id="content-form" class="page-form">
  <form name="contentForm" ng-submit="save()">
    <div class="form-group">
      <label>Title</label>
      <input type="text" name="title" class="form-control" ng-model="content.title" required ng-if="!user.id" />
      <p class="form-control-static" ng-if="user.id">{{content.title}}</p>
    </div>
    <div class="form-group">
      <label>Body</label>
      <textarea name="body" class="form-control" ng-model="content.body"></textarea>
    </div>
    <div class="form-grop clearfix">
      <div class="pull-right">
        <a href="" class="btn btn-warning" ng-if="changed" ng-click="reset();">Reset changes</a>
        <button class="btn" ng-disabled="contentForm.$invalid" ng-class="{'btn-success':!contentForm.$invalid}">Save</button>
      </div>
    </div>
  </form>
</div>
