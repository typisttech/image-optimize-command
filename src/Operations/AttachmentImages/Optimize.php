<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use Spatie\ImageOptimizer\OptimizerChain;
use Symfony\Component\Filesystem\Exception\IOException;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;
use function WP_CLI\Utils\normalize_path;

class Optimize
{
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

        $ids = array_filter($ids, function (int $id): bool {
            // phpcs:ignore
            $isOptimized = $this->repo->isOptimized($id);

            if ($isOptimized) {
                // phpcs:ignore
                $this->logger->warning('Skip: Attachment already optimized - ID: ' . $id);
            }

            return ! $isOptimized;
        });
        $ids = array_filter($ids);

        array_map(function (int $id): void {
            // phpcs:ignore
            $this->optimizeAttachment($id);
        }, $ids);

        $this->logger->info('Finished');
    }

    /**
     * Optimize all images of an attachment.
     *
     * @param int $id The attachment ID.
     *
     * @return void
     */
    protected function optimizeAttachment(int $id): void
    {
        $this->logger->debug('Optimizing images of attachment ID: ' . $id);

        $paths = $this->repo->getPaths($id);

        $normalizedPaths = array_map(function (string $path): string {
            return normalize_path($path);
        }, $paths);

        $results = array_map(function (string $imagePath): bool {
            // phpcs:ignore
            return $this->optimizeImage($imagePath);
        }, $normalizedPaths);

        if (in_array(true, $results, true)) {
            $this->logger->debug('Marking attachment ID: ' . $id . ' as optimized.');
            $this->repo->markAsOptimized($id);
            $this->logger->notice('Optimized images of attachment ID: ' . $id);
        }
    }

    /**
     * Optimize an image.
     *
     * @param string $path Path to the image.
     *
     * @return bool
     */
    protected function optimizeImage(string $path): bool
    {
        try {
            $this->logger->debug('Optimizing image - ' . $path);

            if (! is_readable($path)) {
                $this->logger->error('Image not readable - ' . $path);

                return false;
            }

            // phpcs:ignore WordPress.VIP.FileSystemWritesDisallow.file_ops_is_writable
            if (! is_writable($path)) {
                $this->logger->error('Image not writable - ' . $path);

                return false;
            }

            $this->optimizerChain->optimize($path);

            return true;
            // phpcs:ignore
        } catch (IOException $exception) {
            $this->logger->error('Failed to optimize ' . $path);

            return false;
        }
    }
}
