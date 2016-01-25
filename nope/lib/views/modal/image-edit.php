<nope-modal class="image-editing">
  <nope-modal-body>
    <div>
      <img ng-src="{{theImage.imageUrl}}" class="img-responsive img-thumbnail" />
    </div>
    <div class="btn-group btn-group-xs center-block clearfix">
      <a href="" class="btn btn-default" ng-click="save(90);">
        <i class="fa fa-rotate-left"></i>
      </a>
      <a href="" class="btn btn-default" ng-click="save(-90);">
        <i class="fa fa-rotate-right"></i>
      </a>
    </div>
  </nope-modal-body>
  <nope-modal-footer>
    <a class="btn btn-default btn-sm" nope-modal-close>Close</a>
  </nope-modal-footer>
</nope-modal>
