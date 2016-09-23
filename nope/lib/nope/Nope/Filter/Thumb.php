<?php

// http://image.intervention.io/api/filter
namespace Nope\Filter;

class Thumb implements \Intervention\Image\Filters\FilterInterface {
  private $width;
  private $height;

  /**
   * Creates new instance of filter
   *
   * @param integer $size
   */
  public function __construct($width = null, $height = null) {
    $this->width = $width;
    $this->height = $height;
  }

  /**
   * Applies filter effects to given image
   *
   * @param  Intervention\Image\Image $image
   * @return Intervention\Image\Image
   */
  public function applyFilter(\Intervention\Image\Image $image) {
    if(is_null($this->width) || is_null($this->height)) {
      $image->resize($this->width, $this->height, function ($constraint) {
        $constraint->aspectRatio();
      });
    } else {
      $image->fit($this->width, $this->height, function ($constraint) {
        $constraint->upsize();
      });
    }
    return $image;
  }
}
