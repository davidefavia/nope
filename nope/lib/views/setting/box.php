<?php foreach($fields as $field) { ?>
  <div <?php echo ($field->properties->attributes['ng-if']?"ng-if=\"".$field->properties->attributes['ng-if']."\"":''); ?>>
    <?php if($field->isGroup()) {
      echo $field->draw($ngModel);
    } else { ?>
      <?php if($field->properties->type==='checkbox') { ?>
      <div class="checkbox">
        <?php if($field->properties->description) { ?>
          <p class="control-description"><?php echo $field->properties->description; ?></p>
        <?php } ?>
        <?php echo $field->draw($ngModel); ?>
      </div>
      <?php } else { ?>
      <div class="form-group">
        <?php if($field->properties->label) { ?>
        <label class="control-label"><?php echo  $field->properties->label; ?></label>
        <?php } ?>
        <?php if($field->properties->description) { ?>
          <p class="control-description"><?php echo $field->properties->description; ?></p>
        <?php } ?>
        <?php echo $field->draw($ngModel); ?>
      </div>
      <?php } ?>
    <?php } ?>
  </div>
<?php } ?>
