<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use TypistTech\ImageOptimizeCommand\CLI\Commands\AttachmentCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\CommandNamespace;
use WP_CLI;

if (! class_exists('WP_CLI')) {
    return;
}

WP_CLI::add_command('image-optimize', CommandNamespace::class);
WP_CLI::add_command('image-optimize attachment', AttachmentCommand::class);
