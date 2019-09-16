<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\CLI\LoggerFactory;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\Backup;
use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\Optimize;
use TypistTech\ImageOptimizeCommand\Operations\Backup as BaseBackup;
use TypistTech\ImageOptimizeCommand\Operations\Optimize as BaseOptimize;
use TypistTech\ImageOptimizeCommand\OptimizerChainFactory;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

class AttachmentCommand
{
    /**
     * Optimize specific attachments.
     *
     * ## OPTIONS
     *
     * <id>...
     * : The IDs of attachments to optimize.
     * 
     * [--skip-backup]
     * : Skip generation of backup file.
     * ---
     * default: false
     * ---
     * 
     * ## EXAMPLES
     *
     *     # Optimize attachment ID: 123 (with backup)
     *     $ wp image-optimize attachment 123
     * 
     *     # Optimize attachment ID: 123 --skip-backup (without backup)
     *     $ wp image-optimize attachment 123 --skip-backup
     *
     *     # Optimize multiple attachments (with backup)
     *     $ wp image-optimize attachment 123 223 323
     *
     *     # Optimize multiple attachments (without backup)
     *     $ wp image-optimize attachment 123 223 323 --skip-backup
     */
    public function __invoke($args, $_assocArgs): void
    {
        $skipBackup = (bool) $_assocArgs['skip-backup'];
        $ids = array_map(function (string $id): ?int {
            return is_numeric($id)
                ? (int) $id
                : null;
        }, $args);
        $ids = array_filter($ids);

        $repo = new AttachmentRepository();
        $fileSystem = new Filesystem();
        $logger = LoggerFactory::create();

        if (true !== $skipBackup) {
            $backupOperation = $this->createBackupOperation($repo, $fileSystem, $logger);
        }

        $optimizeOperation = $this->createOptimizeOperation($repo, $fileSystem, $logger);

        if (true !== $skipBackup) {
            $backupOperation->execute(...$ids);
        }

        $optimizeOperation->execute(...$ids);
    }

    /**
     * Creates an backup instance.
     *
     * @return Backup
     */
    protected function createBackupOperation(
        AttachmentRepository $repo,
        Filesystem $filesystem,
        LoggerInterface $logger
    ): Backup {
        $baseBackup = new BaseBackup($filesystem, $logger);

        return new Backup($repo, $baseBackup, $logger);
    }

    /**
     * Creates an optimize instance.
     *
     * @return Optimize
     */
    protected function createOptimizeOperation(
        AttachmentRepository $repo,
        Filesystem $filesystem,
        LoggerInterface $logger
    ): Optimize {
        $optimizerChain = OptimizerChainFactory::create();
        $baseOptimize = new BaseOptimize($optimizerChain, $filesystem, $logger);

        return new Optimize($repo, $baseOptimize, $logger);
    }
}
