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
