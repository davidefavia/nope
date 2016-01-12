<div class="panel panel-default content-detail-panel">
  <div class="panel-heading">
    <h2>{{content.title}}</h2>
  </div>
  <div class="panel-body">
    <div class="content" ng-bind-html="content.parsedBody"></div>
  </div>
  <div class="panel-footer">
    <h6>Creation date: {{content.creationDate}}</h6>
    <h6>Last modification date: {{content.lastModificationDate}}</h6>
  </div>
</div>
