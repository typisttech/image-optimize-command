<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use WP_CLI;

if (! class_exists('WP_CLI')) {
    return;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

WP_CLI::add_command('image-optimize', __NAMESPACE__ . '\ImageOptimizeCommand');
