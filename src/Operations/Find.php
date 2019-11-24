<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations;

use InvalidArgumentException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use TypistTech\ImageOptimizeCommand\LoggerInterface;

use function WP_CLI\Utils\normalize_path;

class Find
{
    /**
     * The finder.
     *
     * @var Finder
     */
    protected $finder;

    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Find constructor.
     *
     * @param Finder          $finder The finder.
     * @param LoggerInterface $logger The logger.
     */
    public function __construct(Finder $finder, LoggerInterface $logger)
    {
        $this->finder = $finder;
        $this->logger = $logger;
    }

    /**
     * Find files under a directory with specific extensions.
     *
     * @param string $directory     Path to search.
     * @param string ...$extensions File extensions to search.
     *
     * @return string[]
     */
    public function execute(string $directory, string ...$extensions): array
    {
        $this->logger->section('Finding images');

        $directory = normalize_path($directory);
        $pattern = sprintf(
            '/\.(%1$s)$/',
            implode('|', $extensions)
        );

        $this->logger->info('Directory: ' . $directory);
        $this->logger->info('Pattern: ' . $pattern);

        $files = $this->find($directory, $pattern);
        $this->logger->notice(count($files) . 'image(s) found.');

        return array_values($files);
    }

    /**
     * Find files under a directory with specific name pattern.
     *
     * @param string $directory Normalize_directory path.
     * @param string $pattern   File name regex.
     *
     * @return string[]
     */
    protected function find(string $directory, string $pattern): array
    {
        try {
            $files = $this->finder->files()->in($directory)->name($pattern);

            return array_map(function (SplFileInfo $file): string {
                return $file->getRealPath();
            }, iterator_to_array($files));
        } catch (InvalidArgumentException $exception) {
            return [];
        }
    }
}
