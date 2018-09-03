<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use TypistTech\ImageOptimizeCommand\LoggerInterface;
use TypistTech\ImageOptimizeCommand\Operations\Optimize as BaseOptimize;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

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
     * The optimizer operation.
     *
     * @var BaseOptimize
     */
    protected $optimize;

    /**
     * Optimize constructor.
     *
     * @param AttachmentRepository $repo     The repo.
     * @param BaseOptimize         $optimize The optimizer operation.
     * @param LoggerInterface      $logger   The logger.
     */
    public function __construct(AttachmentRepository $repo, BaseOptimize $optimize, LoggerInterface $logger)
    {
        $this->repo = $repo;
        $this->optimize = $optimize;
        $this->logger = $logger;
    }

    /**
     * Optimize images of attachments.
     *
     * @param int|int[] ...$ids The attachment IDs.
     *
     * @return void
     */
    public function execute(int ...$ids): void
    {
        $this->logger->section('Optimizing images for ' . count($ids) . ' attachment(s)');

        $nonOptimizedIds = array_filter($ids, function (int $id): bool {
            // phpcs:ignore
            return ! $this->isOptimized($id);
        });

        array_map(function (int $id): void {
            // phpcs:ignore
            $this->optimizeAttachment($id);
        }, $nonOptimizedIds);

        $this->logger->info('Finished');
    }

    /**
     * Whether the attachment is optimized.
     * Log warning if already optimized.
     *
     * @param int $id The attachment ID.
     *
     * @return bool
     */
    protected function isOptimized(int $id): bool
    {
        if ($this->repo->isOptimized($id)) {
            // phpcs:ignore
            $this->logger->warning('Skip: Attachment already optimized - ID: ' . $id);

            return true;
        }

        return false;
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
        $this->logger->section('Optimizing images of attachment ID: ' . $id);

        $paths = $this->repo->getPaths($id);
        $this->optimize->execute(...$paths);

        $this->repo->markAsOptimized($id);
        $this->logger->info('Marked attachment ID: ' . $id . ' as optimized.');
    }
}
