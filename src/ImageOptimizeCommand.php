<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use WP_CLI_Command;

/**
 * Class ImageOptimizeCommand.
 *
 * The main command class.
 */
class ImageOptimizeCommand extends WP_CLI_Command
{
    /**
     * Optimize images
     *
     * ## OPTIONS
     *
     * --limit=<num>
     * : Optimize no more than <num> attachments.
     *
     * ## EXAMPLES
     *
     *     # Optimize 10 attachments
     *     $ wp image-optimize --limit=10
     *
     * @when after_wp_load
     */
    public function __invoke($_args, $assocArgs)
    {
        WP_CLI::success('Good to go ' . $assocArgs['limit'] . ' attachment ahead!');
    }
}
