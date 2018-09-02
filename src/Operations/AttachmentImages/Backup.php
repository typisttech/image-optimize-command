<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use TypistTech\ImageOptimizeCommand\Logger;
use TypistTech\ImageOptimizeCommand\Operations\Backup as BaseBackup;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

class Backup
{
    /**
     * The logger.
     *
     * @var Logger
     */
    protected $logger;

    /**
     * The repo.
     *
     * @var AttachmentRepository
     */
    protected $repo;

    /**
     * The backup operation.
     *
     * @var BaseBackup
     */
    protected $backup;

    /**
     * Backup constructor.
     *
     * @param AttachmentRepository $repo   The attachment repo.
     * @param BaseBackup           $backup The backup operation.
     * @param Logger               $logger The logger.
     */
    public function __construct(AttachmentRepository $repo, BaseBackup $backup, Logger $logger)
    {
        $this->repo = $repo;
        $this->backup = $backup;
        $this->logger = $logger;
    }

    public function execute(int ...$ids): void
    {
        $this->logger->section(
            sprintf(
                'Backing up full sized images for %1$d attachment(s)',
                count($ids)
            )
        );

        $paths = $this->repo->getFullSizedPaths(...$ids);

        $this->backup->execute(...$paths);
    }
}
