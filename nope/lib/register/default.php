<?php

\Nope::registerImageSize('icon', new \Nope\Filter\Thumb(48, 48, false));
\Nope::registerImageSize('thumb', new \Nope\Filter\Thumb(200));

\Nope::setConfig('nope.paths',[
    '{{baseurl}}' => NopeBaseUrl,
    '{{basepath}}' => NopeBasePath,
    '{{uploadspath}}' => UploadsPath,
    '{{themepath}}' => ThemePath
]);
