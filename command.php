<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

if (! class_exists('WP_CLI')) {
    return;
}

$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

WP_CLI::add_command('image-optimize', __NAMESPACE__ . '\ImageOptimizeCommand');
