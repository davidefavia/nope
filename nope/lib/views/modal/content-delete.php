<nope-modal title="Delete content">
  <nope-modal-body>
    <div ng-if="ngModel.id">
      <p>Are you sure you want to delete content "{{ngModel.title}}"?</p>
    </div>
    <div ng-if="!ngModel.id && ngModel.length">
      <p>Are you sure you want to delete {{ngModel.length}} contents?</p>
    </div>
    <p class="text-danger">This operation cannot be undone.</p>
  </nope-modal-body>
  <nope-modal-footer>
  <a class="btn btn-default" nope-modal-close>Close</a>
  <a class="btn btn-danger" ng-click="deleteContent();">Yes, delete</a>
  </nope-modal-footer>
</nope-modal>
