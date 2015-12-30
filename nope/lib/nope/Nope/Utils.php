<?php

namespace Nope;

class Utils {

  const USERNAME_REGEX_PATTERN = '/^([a-z0-9]{3,20})$/';
  // http://stackoverflow.com/questions/46155/validate-email-address-in-javascript
  const EMAIL_REGEX_PATTERN = '/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i';

  static function mergeDirectories($list,$include=false,$prefix=[]) {
    $ds = '/';
    $files = [];
    $i = 0;
    #sort($list);
    foreach($list as $path) {
      if(file_exists($path)) {
        $path = rtrim($path,$ds).$ds;
        if($handle = opendir($path)) {
          while(false !== ($f = readdir($handle))) {
            if($f!='.' && $f!='..') {
              $p = $prefix[$i]!=''?$prefix[$i].$ds:'';
              if(is_file($path.$f)) {
                $rp = rtrim(str_replace(dirname($path.$f), '', $path),$ds).$f;
                $files[$p.$rp] = $path.$f;
              } elseif(is_dir($path.$f)) {
                $tmp = self::mergeDirectories([$path.$f],false,[$p.$f]);
                $files = array_merge($files,$tmp);
              }
            }
          }
          closedir($handle);
        }
      }
    }
    if($include) {
      foreach($files as $f) {
        include_once($f);
      }
    } else {
      return $files;
    }
  }

  static public function scanAndInclude($paths) {
    self::mergeDirectories($paths, true);
  }

  static public function getUniqueFilename($filename,$path,$suffix = 0) {
    $completeFilePath = $path . $filename;
    if(!file_exists($completeFilePath)) {
      return $filename;
    } else {
      $suffix++;
      $p = explode('.',$filename);
      $ext = $p[count($p)-1];
      array_pop($p);
      $filename = implode('.',$p).$suffix.'.'.$ext;
      return self::getUniqueFilename($filename,$path,$suffix);
    }
  }

  static public function getFileExtension($filename) {
    try {
      return pathinfo($filename, PATHINFO_EXTENSION);
    } catch(\Exception $e) {
      $p = explode('.',$filename);
      return array_pop($p);
    }
  }

  static function hashPassword($password,$salt) {
    return hash('sha512',(string)$password.(string)$salt);
  }

  /**
   * http://phpsec.org/articles/2005/password-hashing.html
   */
  static function generateSalt($plainText, $salt = null) {
    $saltLength = 9;
    if ($salt === null) {
      $salt = substr(md5(uniqid(rand(), true)), 0, $saltLength);
    } else {
      $salt = substr($salt, 0, $saltLength);
    }
    return $salt . hash('sha512',$salt . $plainText);
  }

  static function isPathWriteable($path) {
    return is_writeable($path);
  }

  static function makePathWriteable($path, $permissions = 0755) {
    chmod($path, $permissions);
  }

  static function createFolderIfDoesntExist($path, $permissions = 0755) {
    if(!file_exists($path)) {
      mkdir($path, $permissions);
    } else {
      self::makePathWriteable($path, $permissions);
    }
  }

  static function getFullRequestUri($request, $add=null) {
    $uri = $request->getUri();
    return implode(array_map(function($item) {
      return ltrim($item,'/');
    }, [
      $uri->getScheme() . ':/',
      $uri->getHost(),
      $uri->getBasePath(),
      $add
    ]),'/');
  }

  static function getPaginationTerms($request, $rpp = 6) {
    $params = (object) $request->getQueryParams();
    $page = (int) ($params->page? : 1);
    $limit = $page * $rpp;
    $offset = ($page-1) * $rpp;
    return (object) [
      'query' => (string) $params->query,
      'page' => (int) $page,
      'limit' => (int) $limit,
      'offset' => (int) $offset,
      'rpp' => (int) $rpp
    ];
  }

  static function getPaginationMetadata($page, $count, $rpp = 6) {
    $last = ceil($count/$rpp);
    return (object) [
      'first' => 1,
      'last' => $last,
      'actual' => $page,
      'previous' => ($page>1?$page-1:1),
      'next' => ($page<$last?$page+1:$last)
    ];
  }

}
