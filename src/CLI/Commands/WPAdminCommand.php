<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use WP_CLI;

class WPAdminCommand
{
    /**
     * Find and optimize images under wp-admin.
     *
     * ## OPTIONS
     *
     * [--extensions=<extensions>]
     * : File types to optimize, separated by commas.
     * Default: gif,jpeg,jpg,png
     *
     * ## EXAMPLES
     *
     *     # Find and optimize images under wp-admin
     *     $ wp image-optimize wp-admin
     *
     *     # Find and optimize SVGs,PNGs under wp-admin
     *     $ wp image-optimize wp-admin --extensions=svg,png
     */
    public function __invoke($_, $assocArgs): void
    {
        if (! defined('ABSPATH')) {
            WP_CLI::error("Constant 'ABSPATH' not defined. Is WordPress loaded?");
        }

        $directory = constant('ABSPATH') . 'wp-admin';

        $findCommand = new FindCommand();
        $findCommand([$directory], $assocArgs);
    }
}
