<div class="list-group">
  <div class="list-group-item clearfix" ng-class="{'has-image':content.author.cover}">
    <img ng-src="{{content.author.cover.preview.icon}}" class="img-circle" ng-if="content.author.cover" />
    Created by <span class="fullname">{{content.author.prettyName || content.author.username}}</span>
    <br>
    <span ng-if="content.id">{{content.creationDate | nopeMoment}}</span>
    <span ng-if="!content.id">now</span>
  </div>
</div>
