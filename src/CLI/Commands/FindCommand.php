<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use TypistTech\ImageOptimizeCommand\CLI\LoggerFactory;
use TypistTech\ImageOptimizeCommand\Operations\Find;
use TypistTech\ImageOptimizeCommand\Operations\Optimize;
use TypistTech\ImageOptimizeCommand\OptimizerChainFactory;

class FindCommand
{
    /**
     * Find and optimize images under a given directory.
     *
     * ## OPTIONS
     *
     * <directory>
     * : Path to the directory.
     *
     * [--extensions=<extensions>]
     * : File types to optimize, separated by commas.
     * Default: gif,jpeg,jpg,png,webp
     *
     * ## EXAMPLES
     *
     *     # Find and optimize images under /path/to/my/directory
     *     $ wp image-optimize find /path/to/my/directory
     *
     *     # Find and optimize SVGs,PNGs under /path/to/my/directory
     *     $ wp image-optimize find /path/to/my/directory --extensions=svg,png
     */
    public function __invoke($args, $assocArgs): void
    {
        $extensions = $assocArgs['extensions'] ?? 'gif,jpeg,jpg,png,webp';
        $extensions = explode(',', $extensions);

        $logger = LoggerFactory::create();
        $findOperation = new Find(
            new Finder(),
            $logger
        );

        $optimizeOperation = new Optimize(
            OptimizerChainFactory::create(),
            new Filesystem(),
            $logger
        );

        $images = $findOperation->execute($args[0], ...$extensions);
        $optimizeOperation->execute(...$images);
    }
}
