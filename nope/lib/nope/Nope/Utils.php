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

}
