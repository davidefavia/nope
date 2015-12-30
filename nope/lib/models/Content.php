<?php

namespace Nope;

use \RedBeanPHP\R as R;
use \Respect\Validation\Validator as v;
use \Respect\Validation\Exceptions\NestedValidationException;

class Content extends Model {

  const MODELTYPE = 'content';

  function validate() {
    $contentValidator = v::attribute('title', v::alnum()->noWhitespace()->length(1,255));
    try {
      $contentValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

  function jsonSerialize() {
    $json = parent::jsonSerialize();
    $author = $this->getAuthor();
    unset($json->authorId);
    $json->author = $author;
    $json->tags = $this->getTags();
    if($json->coverId) {
      $cover = Media::findById($json->coverId);
      if($cover) {
        unset($cover->model->author_id);
      }
    }
    unset($json->coverId);
    $json->cover = $cover;
    $a = (object) $json;
    unset($a->sharedTag);
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
      $this->model->author = R::load(User::MODELTYPE,$user->id);
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

  private function setTags($tags) {
    if(is_string($tags)) {
      $tags = array_map('trim', explode(',',$tags));
    } elseif(is_array($tags)) {
      $tags = array_map('trim', $tags);
    }
    R::tag($this->model,$tags);
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
        } else {
          if(array_key_exists($f, $body)) {
            $this->$f = $body[$f];
          }
        }
      }
    }
  }

}
