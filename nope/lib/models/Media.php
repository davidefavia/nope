<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use Intervention\Image\ImageManagerStatic as Image;

class Media extends Content {

  const MODELTYPE = 'media';

  function getPath() {
    return NOPE_UPLOADS_DIR . $this->filename;
  }

  function getUrl($cache=true) {
    $t = ($cache?'':'?_='.time());
    return NOPE_UPLOADS_PATH . $this->filename . $t;
  }

  function isImage() {
    $needle = 'image/';
    $length = strlen($needle);
    return (substr($this->mimetype, 0, $length) === $needle);
  }

  function getMetadata() {
    if($this->isImage()) {
      $path = $this->getPath();
      return (object) [
        'exif' => Image::make($path)->exif(),
        'iptc' => Image::make($path)->iptc()
      ];
    }
    return null;
  }

  function delete() {
    parent::delete();
    @unlink($this->getPath());
    $this->deleteCache();
  }

  function deleteCache() {
    try {

    } catch(\Exception $e) {

    }
  }

  function getPreview($type='thumb', $cache=true) {
    $extension = Utils::getFileExtension($this->filename);
    $filename = implode('-',[$type, $this->id]).'.'.$extension;
    $t = ($cache?'':'?_='.time());
    if(file_exists(NOPE_CACHE_DIR . 'uploads/' . $filename)) {
      return NOPE_CACHE_PATH.'uploads/'.$filename . $t;
    } else {
      return NOPE_BASE_PATH . ltrim(NOPE_ADMIN_ROUTE, '/') .'/service/preview/'.$type.'/'.$this->id . $t;
    }
  }

  function beforeSave() {
    $this->metadata = json_encode($this->getMetadata());
    parent::beforeSave();
  }

  function jsonSerialize() {
    $json = parent::jsonSerialize();
    $json->url = $this->getUrl();
    $json->preview = (object) [];
    foreach (\Nope::getConfig('nope.media.size') as $key => $value) {
      $json->preview->$key = $this->getPreview($key);
    }
    $json->isImage = $this->isImage();
    $json->metadata = json_decode($this->metadata);
    $json->starred = (bool) $json->starred;
    unset($json->cover);
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

  static public function findById($id) {
    return self::__to(R::findOne(self::MODELTYPE, 'id = ?', [$id]));
  }

  static public function findAll($filters=[], $limit=-1, $offset=0, &$count=0, $orderBy='id desc') {
    $params = [];
    $sql = self::__getSql($filters, $params);
    if($orderBy) {
      $sql[] = 'order by '.$orderBy;
    }
    $contentsList = R::findAll(self::__getModelType(), implode(' ',$sql),$params);
    return self::__to($contentsList, $limit, $offset, $count);
  }

  static function __getSql($filters, &$params=[], $p = null) {
    $sql = [];
    $filters = (object) $filters;
    if($filters->author) {
      $sql[] = $p.'author_id = ?';
      $params[] = $filters->author->id;
    }
    if($filters->mimetype) {
      if(count($sql)) {
        $sql[] = 'and';
      }
      if(substr($filters->mimetype,0,1)==='!') {
        $sql[] = $p.'mimetype NOT LIKE ?';
        $filters->mimetype = substr($filters->mimetype,1);
      } else {
        $sql[] = $p.'mimetype LIKE ?';
      }
      $params[] = '%' . $filters->mimetype . '%';
    }
    if($filters->text) {
      if(count($sql)) {
        $sql[] = 'and';
      }
      $like = '%' . $filters->text . '%';
      $sql[] = '(title LIKE ? or description LIKE ?)';
      $params[] = $like;
      $params[] = $like;
    }
    return $sql;
  }

}
