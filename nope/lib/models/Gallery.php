<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;


class Gallery extends Content {

  const MODELTYPE = 'gallery';

  function jsonSerialize() {
    $json = parent::jsonSerialize();
    $json->media = $this->getMedia();
    unset($json->sharedMedia);
    unset($json->startPublishingDate);
    unset($json->endPublishingDate);
    return $json;
  }

  function import($body, $fields) {
    if(in_array('media', $fields)) {
      $this->setMedia($body['media']);
      $fields = array_diff($fields, ['media']);
    }
    parent::import($body, $fields);
  }

  private function getMedia() {
    $m = [];
    $rows = R::getAll('SELECT m.* FROM media m LEFT JOIN gallery_media gm ON m.id=gm.media_id WHERE gm.gallery_id='.$this->id.' order by gm.id asc');
    if(count($rows)) {
      foreach ($rows as $media) {
        $me = Media::findById($media['id']);
        $m[] = $me->toJson();
      }
    }
    return $m;
  }

  private function setMedia($value) {
    R::exec('DELETE FROM gallery_media where gallery_id='.$this->id);
    if(count($value)) {
      foreach($value as $media) {
        $me = R::load('media', $media['id']);
        $this->model->sharedMedia[] = $me;
      }
    }
  }

  function beforeSave() {
    // Check unique slug!
    $contentCheckBySlug = self::findBySlug($this->slug);
    if((!$this->id && $contentCheckBySlug) || ($this->id && $contentCheckBySlug && (int) $contentCheckBySlug->id!==(int)$this->id)) {
      $e = new \Exception("Error saving gallery due to existing slug.");
      throw $e;
    }
    parent::beforeSave();
  }



}
