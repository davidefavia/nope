<?php

$app->group(NOPE_ADMIN_ROUTE . '/user', function() {

  $this->post('/login', function ($req, $res) {
    return $res;
  });

  $this->get('/logout', function ($req, $res) {
    return $res;
  })->add(auth());


});
