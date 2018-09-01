<?php

declare(strict_types=1);

require_once codecept_root_dir('vendor/autoload.php'); // Composer autoload.

// Now call the bootstrap method of WP Mock.
WP_Mock::activateStrictMode();
WP_Mock::bootstrap();
