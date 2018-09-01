<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use Spatie\ImageOptimizer\OptimizerChainFactory;
use WP_CLI;
use WP_CLI_Command;

class ImageOptimizeCommand extends WP_CLI_Command
{
    /**
     * Optimize attachment images
     *
     * ## OPTIONS
     *
     * --limit=<num>
     * : Optimize no more than <num> attachments.
     *
     * [--backup]
     * : Whether to backup images.
     *
     * ## EXAMPLES
     *
     *     # Optimize 10 attachments
     *     $ wp image-optimize run --limit=10
     *
     * @when after_wp_load
     */
    public function run($_args, $assocArgs = [])
    {
        $attachmentIds = AttachmentRepository::take((int) $assocArgs['limit']);
        $logger = new Logger();

        if (empty($attachmentIds)) {
            $logger->warning('No unoptimized attachment found. Abort!');

            return;
        }

        $logger->notice(
            sprintf('%d unoptimized attachment(s) found. Starting...', count($attachmentIds))
        );

        $optimizerChain = OptimizerChainFactory::create();
        $optimizerChain->useLogger($logger);

        $backup = isset($assocArgs['backup']);

        // TODO: Extract to its own class.
        array_map(function (int $attachmentId) use ($optimizerChain, $logger, $backup) {
            if ($backup && !AttachmentRepository::backup($attachmentId)) {
                $logger->warning(sprintf('Attachment "%d" could not be backed up. Continue.', $attachmentId));
            }
            array_map(function (string $imagePath) use ($optimizerChain) {
                $optimizerChain->optimize($imagePath);
            }, ImageRepository::pathsFor($attachmentId));

            AttachmentRepository::markAsOptimized($attachmentId);
        }, $attachmentIds);

        $logger->notice(
            sprintf('%d attachment(s) optimized', count($attachmentIds))
        );
    }

    /**
     * Delete boolean flags (meta fields) from all attachments
     *
     * By default, boolean flags (meta fields) are given to attachments
     * after optimization. This is to prevent re-optimizing an already
     * optimized attachment. If you changed the image files
     * (e.g.: resize / regenerate thumbnail), you must first reset their
     * meta flags.
     *
     * ## OPTIONS
     *
     * [--yes]
     * : Answer yes to the confirmation message.
     * 
     * [--restore-backups]
     * : Whether to restore backups if any.
     *
     * ## EXAMPLES
     *
     *     # Optimize after thumbnail regeneration.
     *
     *     $ wp media regenerate --yes
     *     $ wp image-optimize reset --yes --restore-backups
     *     $ wp image-optimize run --limit=9999999
     *
     * @when after_wp_load
     */
    public function reset($_args, $assocArgs = [])
    {
        WP_CLI::confirm('Are you sure you want to drop all wp image-optimize meta flags?', $assocArgs);
        if (isset($assocArgs['restore-backups'])) {
            AttachmentRepository::restore();
        }
        AttachmentRepository::markAllAsUnoptimized();
    }
}
