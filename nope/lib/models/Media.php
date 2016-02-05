<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use Intervention\Image\ImageManagerStatic as Image;

class Media extends Content {

  const MODELTYPE = 'media';

  function isExternal() {
    return ((string) $this->provider)!='';
  }

  function getPath() {
    return NOPE_UPLOADS_DIR . $this->filename;
  }

  function getUrl($cache=true) {
    $t = ($cache?'':'?_='.time());
    return NOPE_UPLOADS_PATH . $this->filename . $t;
  }

  function getAbsoluteUrl($cache=true) {
    if($this->isExternal()) {
      return $this->url;
    }
    return Utils::getBaseUrl() . $this->getUrl($cache);
  }

  function isImage() {
    if($this->isExternal()) {
      return $this->type === 'photo';
    }
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
    $json->url = new String($this->getUrl());
    $json->absoluteUrl = new String($this->getAbsoluteUrl());
    $json->preview = (object) [];
    foreach (\Nope::getConfig('nope.media.size') as $key => $value) {
      $json->preview->$key = new String($this->getPreview($key));
    }
    $json->isImage = $this->isImage();
    $json->isExternal = $this->isExternal();
    $json->metadata = json_decode($this->metadata);
    unset($json->cover);
    return $json;
  }

  function validate() {
    $contentValidator = v::attribute('title', v::length(1,255))
      ->attribute('filename', v::length(1,255));
    try {
      $contentValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

  static function __getSql($filters, &$params=[], $p = null) {
    $sql = parent::__getSql($filters, $params, $p);
    $filters = (object) $filters;
    if($filters->mimetype) {
      if(count($sql)) {
        $sql[] = 'and';
      }
      if(substr($filters->mimetype,0,1)==='!') {
        $sql[] = '('.$p.'mimetype NOT LIKE ? or '.$p.'provider IS NOT NULL)';
        $filters->mimetype = substr($filters->mimetype,1);
        $params[] = '%' . $filters->mimetype . '%';
      } elseif($filters->mimetype==='provider') {
        $sql[] = '('.$p.'provider IS NOT NULL)';
      } else {
        $sql[] = '('.$p.'mimetype LIKE ? and '.$p.'provider is NULL)';
        $params[] = '%' . $filters->mimetype . '%';
      }
    }
    return $sql;
  }

  static public function findAll($filters=[], $limit=-1, $offset=0, &$count=0, $orderBy='starred desc, id desc') {
    return parent::findAll($filters, $limit, $offset, $count, $orderBy);
  }

}
