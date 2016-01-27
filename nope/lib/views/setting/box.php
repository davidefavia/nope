<?php foreach($fields as $field) { ?>
  <?php if($field->properties->type==='checkbox') { ?>
  <div class="checkbox">
    <?php if($field->properties->description) { ?>
      <p class="control-description"><?php echo $field->properties->description; ?></p>
    <?php } ?>
    <?php echo $field->draw('setting.value.'); ?>
  </div>
  <?php } else { ?>
  <div class="form-group">
    <label class="control-label"><?php echo $field->properties->label; ?></label>
    <?php if($field->properties->description) { ?>
      <p class="control-description"><?php echo $field->properties->description; ?></p>
    <?php } ?>
    <?php echo $field->draw('setting.value.'); ?>
  </div>
  <?php } ?>
<?php } ?>
