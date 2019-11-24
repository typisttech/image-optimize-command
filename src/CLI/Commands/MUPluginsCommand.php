<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use WP_CLI;

class MUPluginsCommand
{
    /**
     * Find and optimize images under wp-content/mu-plugins.
     *
     * ## OPTIONS
     *
     * [--extensions=<extensions>]
     * : File types to optimize, separated by commas.
     * Default: gif,jpeg,jpg,png,webp
     *
     * ## EXAMPLES
     *
     *     # Find and optimize images under wp-content/mu-plugins
     *     $ wp image-optimize mu-plugins
     *
     *     # Find and optimize SVGs,PNGs under wp-content/mu-plugins
     *     $ wp image-optimize mu-plugins --extensions=svg,png
     */
    public function __invoke($_, $assocArgs): void
    {
        if (! defined('WPMU_PLUGIN_DIR')) {
            WP_CLI::error("Constant 'WPMU_PLUGIN_DIR' not defined. Is WordPress loaded?");
        }

        $directory = constant('WPMU_PLUGIN_DIR');

        $findCommand = new FindCommand();
        $findCommand([$directory], $assocArgs);
    }
}
