<?php

namespace Nope;

use \RedBeanPHP\R as R;
use \Respect\Validation\Validator as v;
use \Respect\Validation\Exceptions\NestedValidationException;
use Stringy\Stringy as S;

class Content extends Model {

  const MODELTYPE = 'content';

  function validate() {
    $contentValidator = v::attribute('title', v::length(1,255))
      ->attribute('slug', v::regex(Utils::SLUG_REGEX_PATTERN));
    try {
      $contentValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

  function beforeSave() {
    if($this->starred!==false && $this->starred!=true) {
      $this->starred = false;
    }
    if(!$this->priority) {
      $this->priority = 0;
    }
    parent::beforeSave();
  }

  function jsonSerialize() {
    $json = parent::jsonSerialize();
    $json->title = new \Nope\Str($json->title);
    $json->slug = new \Nope\Str($json->slug);
    $author = $this->getAuthor();
    unset($json->authorId);
    $json->author = null;
    if($author) {
      $json->author = $author->toJson();
    }
    $json->tags = $this->getTags();
    if($json->coverId) {
      $cover = Media::findById($json->coverId);
      if($cover) {
        unset($cover->model->author_id);
      }
      $cover = $cover->toJson();
    }
    unset($json->coverId);
    $json->cover = $cover;
    $a = (object) $json;
    unset($a->sharedTag);
    $a->priority = (int) $a->priority;
    $a->starred = (bool) $a->starred;
    $a->startPublishingDate = new DateTime($a->startPublishingDate);
    $a->endPublishingDate = ((string) $a->endPublishingDate==''?null:new DateTime($a->endPublishingDate));
    return $a;
  }

  function getCover() {
    if($this->model->cover_id) {
      return $this->model->fetchAs('media')->cover;
    }
    return null;
  }

  private function setCover($value) {
    if($value['id']) {
      $media = new Media($value['id']);
      if($media) {
        $this->model->cover = $media->model;
      } else {
        $this->model->cover = null;
      }
    } else {
      $this->model->cover_id = null;
    }
  }

  function setAuthor($user) {
    if($user->id) {
      $this->model->author = R::load(User::__getModelType(),$user->id);
    }
  }

  private function getAuthor() {
    if($this->author_id) {
      return User::findByid($this->author_id);
    }
    return null;
  }

  private function getTags() {
    $tags = R::tag($this->model);
    if(count($tags)) {
      $t = [];
      foreach($tags as $tag) {
        $t[] = $tag;
      }
    }
    return $t;
  }

  function setTags($tags) {
    if(is_string($tags)) {
      $tags = array_map('trim', explode(',',$tags));
    } elseif(is_array($tags)) {
      $tags = array_map('trim', $tags);
    }
    R::tag($this->model,$tags);
  }

  function removeTags($tags = null) {
    if(is_string($tags)) {
      $tags = array_map('trim', explode(',',$tags));
    } elseif(is_array($tags)) {
      $tags = array_map('trim', $tags);
    } else {
      $tags = $this->getTags();
    }
    R::untag($this->model, $tags);
  }

  function addTags($tags) {
    if(is_string($tags)) {
      $tags = array_map('trim', explode(',',$tags));
    } elseif(is_array($tags)) {
      $tags = array_map('trim', $tags);
    }
    R::addTags($this->model, $tags);
  }

  function hasTag($tag) {
    return $this->hasTags([$tag]);
  }

  function hasTags($tags) {
    return R::hasTag($this->model, $tags, true);
  }

  public function import($body, $fields) {
    if(is_array($fields)) {
      foreach($fields as $f) {
        if($f === 'tags' && array_key_exists($f, $body)) {
          $this->setTags($body[$f]);
        } elseif($f === 'cover') {
          $this->setCover($body[$f]);
        } elseif($f === 'custom') {
          $this->setModelSettings($body[$f]);
        } else {
          $this->$f = $body[$f];
        }
      }
    }
  }

  static function __getSql($filters, &$params=[], $p = null) {
    $sql = parent::__getSql($filters, $params, $p);
    $filters = (object) $filters;
    if($filters->author) {
      $sql[] = $p.'author_id = ?';
      $params[] = $filters->author->id;
    }
    $filters = (object) $filters;
    if($filters->text) {
      if(count($sql)) {
        $sql[] = 'and';
      }
      $like = '%' . $filters->text . '%';
      $sql[] = '(title LIKE ? or body LIKE ?)';
      $params[] = $like;
      $params[] = $like;
    }
    return $sql;
  }

  static public function findBySlug($slug) {
    return self::__to(R::findOne(self::__getModelType(), 'slug = ?', [$slug]));
  }

  static public function findAll($filters=[], $limit=-1, $offset=0, &$count=0, $orderBy='starred desc, priority desc, id desc') {
    return parent::findAll($filters, $limit, $offset, $count, $orderBy);
  }

}
