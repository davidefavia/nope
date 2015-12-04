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
    return $json;
  }

  function validate() {
    $contentValidator = v::attribute('title', v::length(1,255));
    try {
      $contentValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
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
        $m[] = $me;
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
          /*$this->bean->link('gallery_media', [
            'excluded' => $media['excluded']
          ])->media = $me;*/
        }
      }
    }

  static public function findById($id) {
    return self::__to(R::findOne(self::MODELTYPE, 'id = ?', [$id]));
  }

  static public function findAll($filters=null, $limit=-1, $offset=0, &$count=0, $orderBy='id desc') {
    $filters = (object) $filters;
    $params = [];
    /*if($filters->role) {
      $sql[] = 'role = ?';
      $params[] = $filters->role;
    }*/
    if($orderBy) {
      $sql[] = 'order by '.$orderBy;
    }
    $users = R::findAll(self::MODELTYPE, implode(' ',$sql),$params);
    return self::__to($users, $limit, $offset, $count);
  }



}
