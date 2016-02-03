<?php

namespace Nope;

abstract class Query {

  public static function __callStatic($name, $arguments) {
    $className = explode('\\',static::class);
    return self::__to(call_user_func_array([__NAMESPACE__.'\\'.$className[count($className)-1], $name], $arguments));
  }

  function __to($itemsList) {
    if(is_null($itemsList)) {
      return null;
    }
    if(is_array($itemsList)) {
      $list = [];
      foreach($itemsList as $item) {
        $list[] = $item->toJson();
      }
      return $list;
    }
    return $itemsList->toJson();
  }

}
