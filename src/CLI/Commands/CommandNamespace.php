<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use WP_CLI\Dispatcher\CommandNamespace as BaseCommandNamespace;

/**
 * Check for common mistakes and enforce best practices before take off.
 *
 * ## EXAMPLES
 *
 *     # Optimize specific attachments
 *     $ wp image-optimize attachment 123,223,323
 *
 *     # Optimize certain attachments
 *     $ wp image-optimize batch --limit=10
 */
class CommandNamespace extends BaseCommandNamespace
{
}
