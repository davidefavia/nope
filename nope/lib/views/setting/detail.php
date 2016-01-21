<?php

$properties = $setting->properties;
$fields = $setting->getFields();

?>
<form name="settingForm" ng-submit="save();">
  <div class="panel panel-default content-detail-panel">
    <div class="panel-heading">
      <?php echo $properties->label; ?>
    </div>
    <div class="panel-body">
      <?php foreach($fields as $field) { ?>
      <div class="form-group">
        <label class="control-label"><?php echo $field->properties->label; ?></label>
        <?php if($field->properties->description) { ?>
          <p class="control-description"><?php echo $field->properties->description; ?></p>
        <?php } ?>
        <?php echo $field->draw('setting.value.'); ?>
      </div>
      <?php } ?>
    </div>
    <div class="panel-footer">
      <button class="btn btn-block" ng-disabled="settingForm.$invalid" ng-class="{'btn-success':!settingForm.$invalid}">Save</button>
    </div>
  </div>

</form>
