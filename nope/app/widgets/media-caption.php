<?php if($media) { ?>
<div class="widget widget-media">
  <img src="<?php echo $media->url; ?>" class="responsive <?php echo $attributes->class; ?>" title="<?php echo $media->title; ?>" /><p><?php echo $media->title; ?></p>
</div>
<?php } else { ?>
[No media found]
<?php } ?>
