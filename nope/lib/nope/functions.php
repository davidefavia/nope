<?php

use \Stringy\StaticStringy as S;

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

function getMenuBySlug($slug, $depth = 1) {
  if($depth===1) {
    $menu = \Nope\Query\Menu::findBySlug($slug);
  } else {
    $menu = $slug;
  }
  if($menu) {
    $p = [];
    if(count($menu->items)) {
      foreach ($menu->items as $key => $value) {
        $p[] = getMenuBySlug($value, $depth+1);
      }
    }
    if(S::startsWith($menu->value, '/') || S::startsWith($menu->value, 'http://') || S::startsWith($menu->value, 'https://')) {
      // nothing
    } else {
      $menu->value = NOPE_BASE_PATH . $menu->value;
    }
    $menu->items = $p;
  }
  return $menu;
}

function getSetting($key) {
  $setting = \Nope\Query\Setting::findByKey($key);
  if($setting) {
    return $setting->value;
  }
  return;
}
