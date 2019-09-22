<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use TypistTech\ImageOptimizeCommand\Operations\Backup as BaseBackup;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

class BackupFactory
{
    public const HOOK = 'typist_tech_image_optimized_attachment_images_backup_operation';

    public static function create(
        AttachmentRepository $repo,
        Filesystem $filesystem,
        LoggerInterface $logger
    ): Backup {
        $baseBackup = new BaseBackup($filesystem, $logger);

        return apply_filters(
            static::HOOK,
            new Backup($repo, $baseBackup, $logger),
            $repo,
            $filesystem,
            $logger,
            $baseBackup
        );
    }
}
