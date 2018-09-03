<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use function WP_CLI\Utils\normalize_path;

class Backup
{
    public const ORIGINAL_EXTENSION = '.original';

    /**
     * The logger.
     *
     * @var LoggerInterface
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
     * @param Filesystem      $filesystem The file system.
     * @param LoggerInterface $logger     The logger.
     */
    public function __construct(Filesystem $filesystem, LoggerInterface $logger)
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

        array_map(function (string $path): void {
            // phpcs:ignore
            $this->backup($path);
        }, $normalizedPaths);

        $this->logger->info('Finished');
    }

    /**
     * Backup a file at its directory with `.original` extension.
     *
     * @param string $path Path to the file to be backed up.
     *
     * @return void
     */
    protected function backup(string $path): void
    {
        try {
            $this->logger->debug('Backing up full sized image - ' . $path);

            $isBackupExists = $this->filesystem->exists($path . static::ORIGINAL_EXTENSION);
            if ($isBackupExists) {
                $this->logger->debug('Skip: Backup already exists - ' . $path);

                return;
            }

            $this->filesystem->copy($path, $path . static::ORIGINAL_EXTENSION);
            $this->logger->notice('Backed up full sized image - ' . $path);

            // phpcs:ignore
        } catch (IOException | FileNotFoundException $exception) {
            $this->logger->error('Failed to backup ' . $path);
        }
    }
}
