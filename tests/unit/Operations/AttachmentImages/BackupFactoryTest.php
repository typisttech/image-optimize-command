<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use Codeception\Test\Unit;
use Mockery;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\CLI\Logger;
use TypistTech\ImageOptimizeCommand\Operations\Backup as BaseBackup;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;
use WP_Mock;

class BackupFactoryTest extends Unit
{
    public function testCreate()
    {
        $repo = Mockery::spy(AttachmentRepository::class);
        $filesystem = Mockery::spy(Filesystem::class);
        $logger = Mockery::spy(Logger::class);

        $baseBackup = new BaseBackup($filesystem, $logger);
        $expected = new Backup($repo, $baseBackup, $logger);

        WP_Mock::userFunction(__NAMESPACE__ . '\apply_filters')
               ->with(
                   BackupFactory::HOOK,
                   Mockery::type(Backup::class),
                   $repo,
                   $filesystem,
                   $logger,
                   Mockery::type(BaseBackup::class)
               )
               ->andReturnUsing(function ($_hook, $arg) {
                   return $arg;
               })
               ->once();

        $actual = BackupFactory::create($repo, $filesystem, $logger);

        $this->assertEquals($expected, $actual);
    }
}
