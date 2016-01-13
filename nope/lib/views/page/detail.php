<div class="panel panel-default content-detail-panel">
  <div class="panel-heading clearfix">
    <h2><i class="fa fa-star" ng-if="content.starred"></i> {{content.title}} <a ng-href="#/content/{{contentType}}/{{content.id}}/edit" class="btn btn-default btn-xs pull-right"><i class="fa fa-pencil"></i></a></h2>

  </div>
  <div class="panel-body">
    <div class="content" ng-bind-html="content.parsedBody"></div>
  </div>
  <div class="panel-footer">
    <h6>Creation date: {{content.creationDate}}</h6>
    <h6>Last modification date: {{content.lastModificationDate}}</h6>
  </div>
</div>
