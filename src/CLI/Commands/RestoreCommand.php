<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\CLI\LoggerFactory;
use TypistTech\ImageOptimizeCommand\Operations\AttachmentImages\Restore;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

class RestoreCommand
{
    /**
     * Restore the full sized images of specific attachments.
     *
     * ## OPTIONS
     *
     * <id>...
     * : The IDs of attachments to optimize.
     *
     * ## EXAMPLES
     *
     *     # Optimize attachment ID: 123
     *     $ wp image-optimize restore 123
     *
     *     # Optimize multiple restore attachments
     *     $ wp image-optimize restore 123 223 323
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
        $filesystem = new Filesystem();
        $logger = LoggerFactory::create();

        $restoreOperation = new Restore($repo, $filesystem, $logger);
        $restoreOperation->execute(...$ids);
    }
}
