<nope-modal class="content {{model?'content-'+model:''}}" id="modal-content">
  <nope-modal-body>
    <iframe ng-src="{{url}}"></iframe>
  </nope-modal-body>
  <nope-modal-footer>
    <a href="" class="btn btn-default" nope-modal-close>Close</a>
    <a href="" class="btn btn-success" ng-click="$parent.selection.length?$parent.onSelect(selection):false;" ng-disabled="!$parent.selection.length">Select <span ng-show="$parent.selection.length">({{selection.length}} item<span ng-show="$parent.selection.length>1">s</span>)</span></a>
  </nope-modal-footer>
</nope-modal>
