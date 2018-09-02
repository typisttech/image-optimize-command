<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI\Commands;

use TypistTech\ImageOptimizeCommand\CLI\Logger;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

class BatchCommand
{
    /**
     * Optimize attachments.
     *
     * ## OPTIONS
     *
     * [--limit=<num>]
     * : Optimize no more than <num> attachments.
     * Default: 10
     *
     * ## EXAMPLES
     *
     *     # Find and optimize 10 attachments
     *     $ wp image-optimize batch
     *
     *     # Find and optimize 20 attachments
     *     $ wp image-optimize batch --limit=20
     */
    public function __invoke($_args, $assocArgs): void
    {
        $limit = $assocArgs['limit'] ?? 10;
        $repo = new AttachmentRepository();
        $ids = $repo->take((int) $limit);

        if (empty($ids)) {
            $logger = new Logger();
            $logger->warning('No unoptimized attachment found. Abort!');

            return;
        }

        $attachmentCommand = new AttachmentCommand();
        $attachmentCommand($ids, []);
    }
}
