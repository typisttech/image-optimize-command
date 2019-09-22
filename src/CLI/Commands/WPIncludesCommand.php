<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use WP_CLI;

class WPIncludesCommand
{
    /**
     * Find and optimize images under wp-includes.
     *
     * ## OPTIONS
     *
     * [--extensions=<extensions>]
     * : File types to optimize, separated by commas.
     * Default: gif,jpeg,jpg,png,webp
     *
     * ## EXAMPLES
     *
     *     # Find and optimize images under wp-includes
     *     $ wp image-optimize wp-includes
     *
     *     # Find and optimize SVGs,PNGs under wp-includes
     *     $ wp image-optimize wp-includes --extensions=svg,png
     */
    public function __invoke($_, $assocArgs): void
    {
        if (! defined('ABSPATH')) {
            WP_CLI::error("Constant 'ABSPATH' not defined. Is WordPress loaded?");
        }

        if (! defined('WPINC')) {
            WP_CLI::error("Constant 'WPINC' not defined. Is WordPress loaded?");
        }

        $directory = constant('ABSPATH') . constant('WPINC');

        $findCommand = new FindCommand();
        $findCommand([$directory], $assocArgs);
    }
}
