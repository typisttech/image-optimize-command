<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\Logger;
use function WP_CLI\Utils\normalize_path;

class Backup
{
    protected const SUCCESS = 0;
    protected const ERROR = 1;
    protected const SKIP = 2;
    public const ORIGINAL_EXTENSION = '.original';

    /**
     * The logger.
     *
     * @var Logger
     */
    protected $logger;

    /**
     * The filesystem.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Backup constructor.
     *
     * @param Filesystem $filesystem The file system.
     * @param Logger     $logger     The logger.
     */
    public function __construct(Filesystem $filesystem, Logger $logger)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    /**
     * Duplicate the files as backups.
     *
     * @param string ...$paths Paths to the files.
     */
    public function execute(string ...$paths): void
    {
        $total = count($paths);
        $this->logger->section('Backing up ' . $total . ' full sized image(s)');

        $normalizedPaths = array_map(function (string $path): string {
            return normalize_path($path);
        }, $paths);

        $results = array_map(function (string $path): int {
            return $this->backup($path);
        }, $normalizedPaths);

        $successes = count(array_filter($results, function (int $result): bool {
            return static::SUCCESS === $result;
        }));

        $failures = count(array_filter($results, function (int $result): bool {
            return static::ERROR === $result;
        }));

        $skips = count(array_filter($results, function (int $result): bool {
            return static::SKIP === $result;
        }));

        $this->logger->batchOperationResults('full sized image', 'backup', $total, $successes, $failures, $skips);
    }

    protected function backup(string $path): int
    {
        try {
            $this->logger->debug('Backing up full sized image - ' . $path);

            $isBackupExists = $this->filesystem->exists($path . static::ORIGINAL_EXTENSION);
            if ($isBackupExists) {
                $this->logger->debug('Skip: Backup already exists - ' . $path);

                return static::SKIP;
            }

            $this->filesystem->copy($path, $path . static::ORIGINAL_EXTENSION);
            $this->logger->notice('Backed up full sized image - ' . $path);

            return static::SUCCESS;
        } catch (IOException | FileNotFoundException $exception) {
            $this->logger->error('Failed to backup ' . $path);

            return static::ERROR;
        }
    }
}
