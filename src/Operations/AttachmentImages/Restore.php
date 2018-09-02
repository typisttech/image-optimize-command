<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\Logger;
use TypistTech\ImageOptimizeCommand\Operations\Backup;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;
use function WP_CLI\Utils\normalize_path;

class Restore
{
    protected const SUCCESS = 0;
    protected const ERROR = 1;

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
     * The repo.
     *
     * @var AttachmentRepository
     */
    protected $repo;

    /**
     * Optimize constructor.
     *
     * @param AttachmentRepository $repo       The repo.
     * @param Filesystem           $filesystem The file system.
     * @param Logger               $logger     The logger.
     */
    public function __construct(AttachmentRepository $repo, Filesystem $filesystem, Logger $logger)
    {
        $this->repo = $repo;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    public function execute(int ...$ids): void
    {
        $total = count($ids);
        $this->logger->section('Restoring full sized images for ' . $total . ' attachment(s)');

        $results = array_map(function (int $id): int {
            return $this->restore($id);
        }, $ids);

        $successes = count(array_filter($results, function (int $result): bool {
            return static::SUCCESS === $result;
        }));

        $failures = count(array_filter($results, function (int $result): bool {
            return static::ERROR === $result;
        }));

        $this->logger->batchOperationResults('full sized image', 'restore', $total, $successes, $failures);
    }

    protected function restore(int $id): int
    {
        try {
            $this->logger->debug('Restoring attachment ID: ' . $id);

            $paths = $this->repo->getFullSizedPaths($id);
            $path = $paths[0] ?? null;

            if (empty($path)) {
                $this->logger->error('Full sized image not found for attachment ID: ' . $id);

                return static::ERROR;
            }

            $path = normalize_path($path);
            $this->filesystem->rename(
                $path . Backup::ORIGINAL_EXTENSION,
                $path,
                true
            );

            $this->logger->debug('Marking attachment ID: ' . $id . ' as non-optimized.');
            $this->repo->markAsNonOptimized($id);
            $this->logger->info('Restored attachment ID: ' . $id);

            return static::SUCCESS;
        } catch (IOException $exception) {
            $this->logger->error('Failed to restore full sized image for attachment ID: ' . $id);

            return static::ERROR;
        }
    }
}
