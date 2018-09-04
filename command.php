<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use TypistTech\ImageOptimizeCommand\CLI\Commands\AttachmentCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\BatchCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\CommandNamespace;
use TypistTech\ImageOptimizeCommand\CLI\Commands\FindCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\MUPluginsCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\PluginsCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\ResetCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\RestoreCommand;
use TypistTech\ImageOptimizeCommand\CLI\Commands\WPAdminCommand;
use WP_CLI;

if (! class_exists('WP_CLI')) {
    return;
}

WP_CLI::add_command('image-optimize', CommandNamespace::class);

WP_CLI::add_command('image-optimize attachment', AttachmentCommand::class);
WP_CLI::add_command('image-optimize batch', BatchCommand::class);

WP_CLI::add_command('image-optimize restore', RestoreCommand::class);
WP_CLI::add_command('image-optimize reset', ResetCommand::class);

WP_CLI::add_command('image-optimize find', FindCommand::class);
WP_CLI::add_command('image-optimize wp-admin', WPAdminCommand::class);
WP_CLI::add_command('image-optimize plugins', PluginsCommand::class);
WP_CLI::add_command('image-optimize mu-plugins', MUPluginsCommand::class);
