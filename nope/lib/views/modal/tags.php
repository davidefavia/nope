<nope-modal title="Edit tags">
  <form ng-submit="bulkEditTagsAction(bulkEditTagsOptions.action, bulkEditTagsOptions.tags);">
    <nope-modal-body>
      <div class="form-group">
        <label>For selected media:</label>
        <select class="form-control" ng-model="bulkEditTagsOptions.action">
          <option value="add">Add tags</option>
          <option value="replace">Replace all tags</option>
          <option value="remove">Remove specified tags</option>
          <option value="removeall">Remove all tags</option>
        </select>
      </div>
      <div class="form-group" ng-if="bulkEditTagsOptions.action && bulkEditTagsOptions.action!=='removeall'">
        <label>New tags (comma sepearated):</label>
        <input type="text" class="form-control" ng-model="bulkEditTagsOptions.tags" placeholder="Tags (comma sepearated)" />
      </div>
    </nope-modal-body>
    <nope-modal-footer>
      <a href="" class="btn btn-default" nope-modal-close>Close</a>
      <button type="submit" class="btn btn-danger">Edit tags</button>
    </nope-modal-footer>
  </form>
</nope-modal>
