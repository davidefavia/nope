<?php

namespace Nope;

use Intervention\Image\ImageManagerStatic as Image;

$app->group(NOPE_ADMIN_ROUTE . '/service', function() {

  $this->get('/preview/{type:[a-z]+}/{id:[0-9]+}', function($req, $res, $args) {
    $media = Media::findById($args['id']);
    $filter = \Nope::getConfig('nope.media.size')[$args['type']];
    if($media->isImage()) {
      $path = $media->getPath();
    } else {
      $path = NOPE_LIB_DIR . 'assets/img/icon-200.png';
    }
    $img = Image::cache(function($image) use ($path, $filter) {
      $image->make($path)->filter($filter);
    });
    $body = $res->getBody();
    $body->write($img);
    return $res->withHeader('Content-Type', 'image/jpeg')
      ->withHeader('Content-Length', strlen($img))
      ->withBody($body);
  });

});
