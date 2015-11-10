<?php

function auth() {
  return new \Nope\Middleware\Auth();
}

function path($fileName) {
  return '/' . NOPE_PATH . $fileName;
}
