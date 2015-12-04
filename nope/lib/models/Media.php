<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Media extends Content {

  const MODELTYPE = 'media';

  function getPath() {
    return NOPE_UPLOADS_DIR . $this->model->filename;
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

  function jsonSerialize() {
    $obj = parent::jsonSerialize();
    $obj->url = $this->getUrl();
    $obj->preview = (object) [];
    foreach (\Nope::getConfig('nope.media.size') as $key => $value) {
      $obj->preview->$key = $this->getPreview($key);
    }
    $obj->isimage = $this->isImage();
    unset($obj->cover);
    return $obj;
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
