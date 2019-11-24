<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations;

use Spatie\ImageOptimizer\OptimizerChain;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\LoggerInterface;

use function WP_CLI\Utils\normalize_path;

class Optimize
{
    /**
     * The optimizer chain.
     *
     * @var OptimizerChain
     */
    protected $optimizerChain;

    /**
     * The filesystem.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Optimize constructor.
     *
     * @param OptimizerChain  $optimizerChain The optimizer chain.
     * @param Filesystem      $filesystem     The file system.
     * @param LoggerInterface $logger         The logger.
     */
    public function __construct(OptimizerChain $optimizerChain, Filesystem $filesystem, LoggerInterface $logger)
    {
        $this->optimizerChain = $optimizerChain;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    /**
     * Optimize the files.
     *
     * @param string ...$paths Paths to the files.
     */
    public function execute(string ...$paths): void
    {
        $total = count($paths);
        $this->logger->section('Optimizing ' . $total . ' file(s)');

        $normalizedPaths = array_map(function (string $path): string {
            return normalize_path($path);
        }, $paths);

        $validPaths = array_filter($normalizedPaths, function (string $path): bool {
            return $this->isValid($path);
        });

        array_map(function (string $path): void {
            $this->logger->debug('Optimizing image - ' . $path);
            $this->optimizerChain->optimize($path);
        }, $validPaths);

        $this->logger->info('Finished');
    }

    /**
     * Whether the file exists and readable and writable.
     *
     * @param string $path Path to the file.
     *
     * @return bool
     */
    protected function isValid(string $path): bool
    {
        try {
            $this->logger->debug('Checking image - ' . $path);

            $isExists = $this->filesystem->exists($path);
            if (! $isExists) {
                $this->logger->error('Image not exist - ' . $path);

                return false;
            }

            if (! is_readable($path)) {
                $this->logger->error('Image not readable - ' . $path);

                return false;
            }

            // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_is_writable
            if (! is_writable($path)) {
                $this->logger->error('Image not writable - ' . $path);

                return false;
            }

            return true;
        } catch (IOException $exception) {
            $this->logger->error('Unable to optimize ' . $path);

            return false;
        }
    }
}
