<?php

function auth() {
  return new \Nope\Middleware\Auth();
}

function install() {
  return new \Nope\Middleware\Install();
}

function path($fileName) {
  return NOPE_PATH . $fileName;
}

function adminRoute($route) {
  return NOPE_BASE_PATH . ltrim(NOPE_ADMIN_ROUTE, '/') . '/' . ltrim($route, '/');
}

function redirect($request, $response, $path) {
  return $response->withStatus(302)->withHeader('Location', $request->getUri()->getBasePath() . $path);
}

function asset($fileName) {
  return NOPE_THEME_PATH . $fileName;
}

function doWidget($string,$custom=null) {
  preg_match_all("/\[.*?\]/",$string,$out, PREG_PATTERN_ORDER);
  $loadedWidgets = \Nope::getConfig('nope.widgets');
  $loadedWidgets = $loadedWidgets?:[];
  if(count($out[0])) {
    foreach($out[0] as $item) {
      $o = [];
      preg_match_all("/\[n:([0-9a-z]+){1} (.+)\]/",$item,$o, PREG_PATTERN_ORDER);
      $type = $o[1][0];
      $args = $o[2][0];
      if($args) {
        $x = new \SimpleXMLElement("<nope $args />");
        $attributes = (object) current($x->attributes());
      }
      $w = $loadedWidgets[$type];
      if($loadedWidgets[$type]) {
        $widget = new \Nope\Widget($type,$attributes);
        if($w['data'] && is_callable($w['data'])) {
          $widget->data = $w['data']($widget->attributes);
        }
        $html = $widget->getHtml();
        $string = str_replace($item,$html,$string);
      }
    }
  }
  return $string;
}

function doPaths($string) {
  $paths = \Nope::getConfig('nope.paths');
  if(count($paths)) {
    foreach($paths as $key => $value) {
      $string = str_replace($key,$value,$string);
    }
  }
  return $string;
}
