<?php

namespace Nope;

class Utils {

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

}
