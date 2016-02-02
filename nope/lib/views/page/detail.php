<div class="panel panel-default">
  <div class="panel-heading content-author">
    <nope-author content="content" class="pull-left"></nope-author>
    <div class="pull-right btn-group btn-group-xs toolbar">
      <a ng-href="{{content.fullUrl + '?preview=1'}}" class="btn" target="_blank"><i class="fa fa-link"></i></a>
      <a ng-href="#/content/{{contentType}}/edit/{{content.id}}" class="btn"><i class="fa fa-pencil"></i></a>
      <a ng-click="content.starred=!content.starred;$parent.save(content);" class="btn star"><i class="fa" ng-class="{'fa-star-o':!content.starred,'fa-star':content.starred}"></i></a>
      <a href="" nope-content-delete="$parent.deleteContentOnClick(content)" ng-model="content" class="btn text-danger"><i class="fa fa-trash"></i></a>
    </div>
  </div>
  <div class="panel-body content-preview">
    <iframe src="{{content.fullUrl + '?preview=1'}}"></iframe>
  </div>
</div>
