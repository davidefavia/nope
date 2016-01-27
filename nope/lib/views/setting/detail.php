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
      <?php include 'box.php'; ?>
    </div>
    <div class="panel-footer">
      <button class="btn btn-block" ng-disabled="settingForm.$invalid" ng-class="{'btn-success':!settingForm.$invalid}">Save</button>
    </div>
  </div>

</form>
