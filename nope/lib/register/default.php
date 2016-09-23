<?php

\Nope::registerImageSize('icon', [
  'filter' => new \Nope\Filter\Thumb(48, 48),
  'cache' => 60
]);
\Nope::registerImageSize('profile', [
  'filter' => new \Nope\Filter\Thumb(96,96),
  'cache' => 60
]);
\Nope::registerImageSize('thumb', [
  'filter' => new \Nope\Filter\Thumb(200),
  'cache' => 60
]);

\Nope::registerTextFormat('html', [
  'key' => 'html',
  'label' => 'Html',
  'parser' => false
]);
\Nope::registerTextFormat('markdown', [
  'key' => 'markdown',
  'label' => 'Markdown',
  'parser' => new \Nope\Format\Markdown()
]);

\Nope::setConfig('nope.paths',[
  '{{baseurl}}' => NOPE_INDEX,
  '{{basepath}}' => NOPE_BASE_PATH,
  '{{uploadspath}}' => NOPE_UPLOADS_PATH,
  '{{themepath}}' => NOPE_THEME_PATH
]);
