<?php

namespace Nope;

\Nope::registerWidget('media',[
  'label' => 'Media',
  'data' => function($attributes) {
      if($attributes->id) {
          $media = Query\Media::findById($attributes->id);
      } elseif($attributes->url) {
          $media = $attributes;
      }
      return ['media' => $media];
  }
]);

\Nope::registerWidget('gallery',[
  'label' => 'Gallery',
  'data' => function($attributes) {
    if((int) $attributes->id) {
      $gallery = Query\Gallery::findById((int) $attributes->id);
    } else if($attributes->slug) {
      $gallery = Query\Gallery::findBySlug($attributes->slug);
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
