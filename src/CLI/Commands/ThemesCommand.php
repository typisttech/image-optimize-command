<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

class ThemesCommand
{
    /**
     * Find and optimize images under wp-content/themes.
     *
     * ## OPTIONS
     *
     * [--extensions=<extensions>]
     * : File types to optimize, separated by commas.
     * Default: gif,jpeg,jpg,png,webp
     *
     * ## EXAMPLES
     *
     *     # Find and optimize images under wp-content/themes
     *     $ wp image-optimize themes
     *
     *     # Find and optimize SVGs,PNGs under wp-content/themes
     *     $ wp image-optimize themes --extensions=svg,png
     */
    public function __invoke($_, $assocArgs): void
    {
        $directory = get_theme_root();

        $findCommand = new FindCommand();
        $findCommand([$directory], $assocArgs);
    }
}
