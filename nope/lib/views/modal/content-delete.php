<nope-modal title="Delete content">
  <nope-modal-body>
    <p>Are you sure to delete content "{{ngModel.title}}"?</p>
  </nope-modal-body>
  <nope-modal-footer>
  <a class="btn btn-default" nope-modal-close>Close</a>
  <a class="btn btn-danger" ng-click="deleteContent();">Yes, delete</a>
  </nope-modal-footer>
</nope-modal>
