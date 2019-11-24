<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use WP_CLI;

class PluginsCommand
{
    /**
     * Find and optimize images under wp-content/plugins.
     *
     * ## OPTIONS
     *
     * [--extensions=<extensions>]
     * : File types to optimize, separated by commas.
     * Default: gif,jpeg,jpg,png,webp
     *
     * ## EXAMPLES
     *
     *     # Find and optimize images under wp-content/plugins
     *     $ wp image-optimize plugins
     *
     *     # Find and optimize SVGs,PNGs under wp-content/plugins
     *     $ wp image-optimize plugins --extensions=svg,png
     */
    public function __invoke($_, $assocArgs): void
    {
        if (! defined('WP_PLUGIN_DIR')) {
            WP_CLI::error("Constant 'WP_PLUGIN_DIR' not defined. Is WordPress loaded?");
        }

        $directory = constant('WP_PLUGIN_DIR');

        $findCommand = new FindCommand();
        $findCommand([$directory], $assocArgs);
    }
}
