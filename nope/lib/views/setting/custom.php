<div <?php echo ($setting->properties->attributes['ng-if']?"ng-if=\"".$setting->properties->attributes['ng-if']."\"":''); ?>>
  <div class="panel panel-default" <?php echo $setting->getAttributesList(); ?>>
    <div class="panel-body">
      <?php include 'box.php'; ?>
    </div>
  </div>
</div>
