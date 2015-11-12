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

function redirect($request, $response, $path) {
  return $response->withStatus(302)->withHeader('Location', $request->getUri()->getBasePath() . $path);
}

function hashPassword($password,$salt) {
    return hash('sha512',(string)$password.(string)$salt);
}

/**
 * http://phpsec.org/articles/2005/password-hashing.html
 */
function generateSalt($plainText, $salt = null) {
  $saltLength = 9;
  if ($salt === null) {
    $salt = substr(md5(uniqid(rand(), true)), 0, $saltLength);
  } else {
    $salt = substr($salt, 0, $saltLength);
  }
  return $salt . hash('sha512',$salt . $plainText);
}
