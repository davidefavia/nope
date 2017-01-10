<nope-modal title="User content">
  <nope-modal-body>
    <p>Are you sure to delete user "{{ngModel.username}}"?</p>
    <p class="text-danger">This operation cannot be undone.</p>
  </nope-modal-body>
  <nope-modal-footer>
  <a href="" class="btn btn-secondary btn-lg" nope-modal-close>Close</a>
  <a href="" class="btn btn-danger btn-lg" ng-click="deleteUser();">Yes, delete</a>
  </nope-modal-footer>
</nope-modal>
