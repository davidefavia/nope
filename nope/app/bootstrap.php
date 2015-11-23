<?php

\Nope\Utils::mergeDirectories([NOPE_APP_DIR . 'register'], true);

\Nope::registerRoute(NOPE_APP_DIR . 'routes/index.php');
