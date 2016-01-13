<?php

\Nope::registerImageSize('icon', new \Nope\Filter\Thumb(48, 48));
\Nope::registerImageSize('profile', new \Nope\Filter\Thumb(96,96));
\Nope::registerImageSize('thumb', new \Nope\Filter\Thumb(200));

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
