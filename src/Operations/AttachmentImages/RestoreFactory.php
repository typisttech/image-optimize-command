<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;

class RestoreFactory
{
    public const HOOK = 'TypistTech/ImageOptimizeCommand/Operations/AttachmentImages/Restore';

    public static function create(
        AttachmentRepository $repo,
        Filesystem $filesystem,
        LoggerInterface $logger
    ): Restore {
        return apply_filters(
            static::HOOK,
            new Restore($repo, $filesystem, $logger),
            $repo,
            $filesystem,
            $logger
        );
    }
}
