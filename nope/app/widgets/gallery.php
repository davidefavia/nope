<div class="widget widget-gallery">
<h4>Gallery: <?php echo $gallery->title; ?></h4>
<?php

$size = ($attributes->size?:'icon');

if(count($gallery->media)) {
    foreach($gallery->media as $media) {
?>
<img src="<?php echo $media->preview->{$size}; ?>" />
<?php } } else { ?>
<p>[No media found]</p>
<?php } ?>
</div>
