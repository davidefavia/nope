<div class="nope-author text-muted" ng-class="{'has-image':content.author.cover}">Created by <span class="fullname">{{content.author.prettyName || content.author.username}}</span>
  <span ng-if="content.id">{{content.creationDate | nopeMoment}}</span>
  <span ng-if="!content.id">now</span>
</div>
