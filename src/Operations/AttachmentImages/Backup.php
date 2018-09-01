<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use TypistTech\ImageOptimizeCommand\Logger;
use TypistTech\ImageOptimizeCommand\Operations\Backup as BackupOperation;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentImagePathRepository;

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
     * @var AttachmentImagePathRepository
     */
    protected $repo;

    /**
     * The backup operation.
     *
     * @var BackupOperation
     */
    protected $backup;

    /**
     * Backup constructor.
     *
     * @param AttachmentImagePathRepository $repo   The attachment image repo.
     * @param BackupOperation               $backup The backup operation.
     * @param Logger                        $logger The logger.
     */
    public function __construct(AttachmentImagePathRepository $repo, BackupOperation $backup, Logger $logger)
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

        $paths = $this->repo->getFullSized(...$ids);

        $this->backup->execute(...$paths);
    }
}
