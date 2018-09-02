<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use TypistTech\ImageOptimizeCommand\CLI\Logger;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

class BatchRestoreCommand
{
    /**
     * Restore the full sized images of certain number of attachments.
     *
     * ## OPTIONS
     *
     * [--limit=<num>]
     * : Optimize no more than <num> attachments.
     * Default: 10
     *
     * ## EXAMPLES
     *
     *     # Find and restore 10 attachments
     *     $ wp image-optimize batch-restore
     *
     *     # Find and restore 20 attachments
     *     $ wp image-optimize batch-restore --limit=20
     */
    public function __invoke($_args, $assocArgs): void
    {
        $limit = $assocArgs['limit'] ?? 10;
        $repo = new AttachmentRepository();
        $ids = $repo->takeOptimized((int) $limit);

        if (empty($ids)) {
            $logger = new Logger();
            $logger->warning('No optimized attachment found. Abort!');

            return;
        }

        $restoreCommand = new RestoreCommand();
        $restoreCommand($ids, []);
    }
}
