<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use TypistTech\ImageOptimizeCommand\CLI\Commands\AttachmentCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\BatchCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\BatchRestoreCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\CommandNamespace;
use TypistTech\ImageOptimizeCommand\CLI\Commands\RestoreCommand;
use WP_CLI;

if (! class_exists('WP_CLI')) {
    return;
}

WP_CLI::add_command('image-optimize', CommandNamespace::class);
WP_CLI::add_command('image-optimize attachment', AttachmentCommand::class);
WP_CLI::add_command('image-optimize batch', BatchCommand::class);
WP_CLI::add_command('image-optimize batch-restore', BatchRestoreCommand::class);
WP_CLI::add_command('image-optimize restore', RestoreCommand::class);
