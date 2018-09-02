<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use Spatie\ImageOptimizer\OptimizerChain;
use Symfony\Component\Filesystem\Exception\IOException;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;
use function WP_CLI\Utils\normalize_path;

/**
 * TODO: Refactor this class.
 */
class Optimize
{
    protected const SUCCESS = 0;
    protected const ERROR = 1;

    /**
     * The logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * The repo.
     *
     * @var AttachmentRepository
     */
    protected $repo;

    /**
     * The optimizer chain.
     *
     * @var OptimizerChain
     */
    protected $optimizerChain;

    /**
     * Optimize constructor.
     *
     * @param AttachmentRepository $repo           The repo.
     * @param OptimizerChain       $optimizerChain The optimizer chain.
     * @param LoggerInterface      $logger         The logger.
     */
    public function __construct(AttachmentRepository $repo, OptimizerChain $optimizerChain, LoggerInterface $logger)
    {
        $this->repo = $repo;
        $this->optimizerChain = $optimizerChain;
        $this->logger = $logger;
    }

    /**
     * Optimize images of attachments.
     *
     * @param int ...$ids The attachment IDs.
     *
     * @return void
     */
    public function execute(int ...$ids): void
    {
        $this->logger->section('Optimizing images for ' . count($ids) . ' attachment(s)');

        $results = array_map(function (int $id): array {
            // phpcs:ignore
            return $this->optimizeAttachment($id);
        }, $ids);

        $results = $this->flattenResults($results);

        $successes = count(array_filter($results, function (int $result): bool {
            return static::SUCCESS === $result;
        }));

        $failures = count(array_filter($results, function (int $result): bool {
            return static::ERROR === $result;
        }));

        $this->logger->batchOperationResults(
            'image',
            'optimize',
            count($results),
            $successes,
            $failures
        );
    }

    /**
     * Optimize all images of an attachment.
     *
     * @param int $id The attachment ID.
     *
     * @return int[]
     */
    protected function optimizeAttachment(int $id): array
    {
        $this->logger->debug('Optimizing images of attachment ID: ' . $id);

        $paths = $this->repo->getPaths($id);

        $normalizedPaths = array_map(function (string $path): string {
            return normalize_path($path);
        }, $paths);

        $results = array_map(function (string $imagePath): int {
            // phpcs:ignore
            return $this->optimizeImage($imagePath);
        }, $normalizedPaths);

        if (in_array(static::SUCCESS, $results, true)) {
            $this->logger->debug('Marking attachment ID: ' . $id . ' as optimized.');
            $this->repo->markAsOptimized($id);
            $this->logger->notice('Optimized images of attachment ID: ' . $id);
        }

        return $results;
    }

    /**
     * Optimize an image.
     *
     * @param string $path Path to the image.
     *
     * @return int
     */
    protected function optimizeImage(string $path): int
    {
        try {
            $this->logger->debug('Optimizing image - ' . $path);

            if (! is_readable($path)) {
                $this->logger->error('Image not readable - ' . $path);

                return static::ERROR;
            }

            // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_is_writable
            if (! is_writable($path)) {
                $this->logger->error('Image not writable - ' . $path);

                return static::ERROR;
            }

            $this->optimizerChain->optimize($path);

            return static::SUCCESS;
            // phpcs:ignore
        } catch (IOException $exception) {
            $this->logger->error('Failed to optimize ' . $path);

            return static::ERROR;
        }
    }

    /**
     * Flatten result arrays.
     *
     * @param array $results Array of result arrays.
     *
     * @return int[]
     */
    protected function flattenResults(array $results): array
    {
        switch (count($results)) {
            case 0:
                return [];
            case 1:
                return $results;
            default:
                return array_merge(...$results);
        }
    }
}
