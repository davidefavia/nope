<?php

\Nope\Utils::scanAndInclude([NOPE_APP_DIR . 'register']);

\Nope::registerRoute(NOPE_APP_DIR . 'routes/index.php');
