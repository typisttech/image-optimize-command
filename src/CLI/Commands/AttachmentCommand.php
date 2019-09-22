<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\CLI\LoggerFactory;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\BackupFactory;
use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\Optimize;
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
        $fileSystem = new Filesystem();
        $logger = LoggerFactory::create();

        $backupOperation = BackupFactory::create($repo, $fileSystem, $logger);
        $optimizeOperation = $this->createOptimizeOperation($repo, $fileSystem, $logger);

        $backupOperation->execute(...$ids);
        $optimizeOperation->execute(...$ids);
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
