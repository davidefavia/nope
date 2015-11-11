<?php

function auth() {
  return new \Nope\Middleware\Auth();
}

function install() {
  return new \Nope\Middleware\Install();
}

function path($fileName) {
  return '/' . NOPE_PATH . $fileName;
}
