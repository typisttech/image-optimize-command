<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\CLI\LoggerFactory;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\Backup;
use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\Optimize;
use TypistTech\ImageOptimizeCommand\Operations\Backup as BaseBackup;
use TypistTech\ImageOptimizeCommand\OptimizerChainFactory;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

class AttachmentCommand
{
    /**
     * Optimize attachments.
     *
     * ## OPTIONS
     *
     * <id>...
     * : The IDs of attachments to optimize.
     *
     * ## EXAMPLES
     *
     *     # Optimize attachment ID: 123
     *     $ wp image-optimize attachment 123
     *
     *     # Optimize multiple attachments
     *     $ wp image-optimize attachment 123 223 323
     */
    public function __invoke($args, $_assocArgs): void
    {
        $ids = array_map(function (string $id): ?int {
            return is_numeric($id)
                ? (int) $id
                : null;
        }, $args);
        $ids = array_filter($ids);

        $repo = new AttachmentRepository();
        $logger = LoggerFactory::create();

        $backupOperation = $this->createBackupOperation($repo, $logger);
        $backupOperation->execute(...$ids);

        $optimizeOperation = $this->createOptimizeOperation($repo, $logger);
        $optimizeOperation->execute(...$ids);
    }

    protected function createBackupOperation(AttachmentRepository $repo, LoggerInterface $logger): Backup
    {
        $fileSystem = new Filesystem();
        $baseBackup = new BaseBackup($fileSystem, $logger);

        return new Backup($repo, $baseBackup, $logger);
    }

    protected function createOptimizeOperation(AttachmentRepository $repo, LoggerInterface $logger): Optimize
    {
        $optimizerChain = OptimizerChainFactory::create();

        return new Optimize($repo, $optimizerChain, $logger);
    }
}
