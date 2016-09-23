<?php

if($content) {
  $title = $content->title;
}

?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $title; ?></title>
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
  <link rel="stylesheet" href="<?php echo themePath('assets/css/normalize.css'); ?>">
  <link rel="stylesheet" href="<?php echo themePath('assets/css/milligram.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo themePath('assets/css/style.min.css'); ?>">
</head>
<body>
  <main class="wrapper">
