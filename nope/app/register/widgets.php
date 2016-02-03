<?php

\Nope::registerWidget('media',[
  'label' => 'Media',
  'model' => 'media',
  'data' => function($attributes) {
      if($attributes->id) {
          $media = \Nope\Query\Media::findById($attributes->id);
      } elseif($attributes->url) {
          $media = $attributes;
      }
      return ['media' => $media];
  }
]);

\Nope::registerWidget('gallery',[
  'label' => 'Gallery',
  'model' => 'gallery',
  'options' => [
    'size' => [
      'label' => 'Size',
      'value' => array_keys(\Nope::getConfig('nope.media.size'))
    ]
  ],
  'data' => function($attributes) {
    if($attributes->id) {
      $gallery = \QueryGallery::findById($attributes->id);
    } else if($attributes->slug) {
      $gallery = \QueryGallery::findBySlug($attributes->slug);
    }
    return ['gallery' => $gallery];
  }
]);

\Nope::registerWidget('youtube', [
  'label' => 'YouTube'
]);
\Nope::registerWidget('vimeo', [
  'label' => 'Vimeo'
]);
