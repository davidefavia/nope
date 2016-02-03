<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class TextContent extends Content {

  const MODELTYPE = 'textcontent';

  function jsonSerialize() {
    $json = parent::jsonSerialize();
    #$json->body = new Text($json->body, (string) $json->format);
    $json->realStatus = new String($this->calculateStatus());
    $json->parsedBody = (new Text($json->body, (string) $json->format))->toHTML();
    $json->fullUrl = new String($this->getFullUrl());
    $json->status = new String($this->status);
    $json->format = new String($this->format);
    return $json;
  }

  function getFullUrl() {
    return Utils::getFullBaseUrl() . $this->slug;
  }

  function validate() {
    $contentValidator = v::attribute('title', v::length(1,255))
      ->attribute('slug', v::regex(Utils::SLUG_REGEX_PATTERN))
      ->attribute('format', v::length(1,255))
      ->attribute('status', v::length(1,255));
    try {
      $contentValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

  static function __getSql($filters, &$params=[], $p = null) {
    $sql = [];
    $filters = (object) $filters;
    if($filters->author) {
      $sql[] = $p.'author_id = ?';
      $params[] = $filters->author->id;
    }
    if($filters->excluded) {
      if(count($sql)) {
        $sql[] = 'and';
      }
      $sql[] = $p.'id NOT in (' . R::genSlots($filters->excluded) . ')';
      foreach ($filters->excluded as $value) {
        $params[] = $value;
      }
    }
    if($filters->status) {
      if(count($sql)) {
        $sql[] = 'and';
      }
      $sql[] = $p.'status = ?';
      $params[] = $filters->status;
    } elseif($filters->realStatus) {
      if(count($sql)) {
        $sql[] = 'and';
      }
      $now = (new \DateTime())->format('Y-m-d H:i:s');
      if($filters->realStatus=='published') {
        $sql[] = $p.'status=? and '.$p.'start_publishing_date <= ? and ('.$p.'end_publishing_date>=? or '.$p.'end_publishing_date IS NULL)';
        $params[] = 'published';
        $params[] = $now;
      } elseif($filters->realstatus=='scheduled') {
        $sql[] = $p.'status=? and '.$p.'start_publishing_date > ?';
        $params[] = 'published';
      } elseif($filters->realstatus=='expired') {
        $sql[] = $p.'status=? and '.$p.'end_publishing_date < ?';
        $params[] = 'published';
      } elseif($filters->realstatus=='draft-published') {
        $sql[] = $p.'status=? and '.$p.'start_publishing_date <= ? and ('.$p.'end_publishing_date>=? or '.$p.'end_publishing_date IS NULL)';
        $params[] = 'draft';
        $params[] = $now;
      } elseif($filters->realstatus=='draft-scheduled') {
        $sql[] = $p.'status=? and '.$p.'start_publishing_date > ?';
        $params[] = 'draft';
      } elseif($filters->realstatus=='draft-expired') {
        $sql[] = $p.'status=? and '.$p.'end_publishing_date > ?';
        $params[] = 'draft';
      }
      $params[] = $now;
    }
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

  function calculateStatus() {
    $now = (int) (new \Datetime())->format('U');
    $start = (int) (new \Datetime($this->start_publishing_date))->format('U');
    $end = (int) (new \Datetime($this->end_publishing_date))->format('U');
    $isDraft = ($this->status=='draft');
    if($isDraft) {
      if($end) {
        if($start<=$now && $end>=$now) {
          $status = 'draft-published';
        } elseif($start<=$now && $now>$end) {
          $status = 'draft-expired';
        } elseif($start>$now) {
          $status = 'draft-scheduled';
        }
      } else {
        if($start>$now) {
          $status = 'draft-scheduled';
        } else {
          $status = 'draft-published';
        }
      }
    } else {
      $status = 'published';
      if($end) {
        if($start<=$now && $end>=$now) {
          #$status = 'published';
        } elseif($start<=$now && $now>$end) {
          $status = 'expired';
        } elseif($start>$now) {
          $status = 'scheduled';
        }
      } else {
        if($start>$now) {
          $status = 'scheduled';
        } else {
          #$status = 'published';
        }
      }
    }
    return $status;
  }

  function beforeSave() {
    // Check unique slug!
    $contentCheckBySlug = self::findBySlug($this->slug);
    if((!$this->id && $contentCheckBySlug) || ($this->id && $contentCheckBySlug && (int) $contentCheckBySlug->id!==(int)$this->id)) {
      $e = new \Exception("Error saving content due to existing slug.");
      throw $e;
    }
    if(!$this->startPublishingDate) {
      $this->startPublishingDate = new \Nope\DateTime();
    }
    parent::beforeSave();
  }

}
