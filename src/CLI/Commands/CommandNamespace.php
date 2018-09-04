<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use WP_CLI\Dispatcher\CommandNamespace as BaseCommandNamespace;

/**
 * Optimize images.
 *
 * ## EXAMPLES
 *
 *     # Optimize specific attachments
 *     $ wp image-optimize attachment 123 223 323
 *
 *     # Optimize certain number of attachments
 *     $ wp image-optimize batch --limit=20
 *
 *     # Restore the full sized images of specific attachments.
 *     $ wp image-optimize restore 123 223 323
 *
 *     # Restore all full sized images and drop all meta flags
 *     $ wp image-optimize reset
 *
 *     # Find and optimize images under a given directory.
 *     $ wp image-optimize find /path/to/my/directory
 *
 *     # Find and optimize images under wp-content/mu-plugins
 *     $ wp image-optimize mu-plugins
 *
 *     # Find and optimize images under wp-content/plugins.
 *     $ wp image-optimize plugins
 *
 *     # Find and optimize images under wp-content/themes
 *     $ wp image-optimize themes
 *
 *     # Find and optimize images under wp-admin.
 *     $ wp image-optimize wp-admin
 *
 *     # Find and optimize images under wp-includes.
 *     $ wp image-optimize wp-includes
 */
class CommandNamespace extends BaseCommandNamespace
{
}
