<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations\AttachmentImages;

use Codeception\Module\Filesystem as FilesystemModule;
use Codeception\Test\Unit;
use Mockery;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\CLI\Logger;
use TypistTech\ImageOptimizeCommand\Repositories\AttachmentRepository;
use WP_Mock;

class RestoreTest extends Unit
{
    /**
     * @var \TypistTech\ImageOptimizeCommand\UnitTester
     */
    protected $tester;

    /**
     * @var FilesystemModule
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $testDir;

    public function testRestoreSuccess()
    {
        $repo = Mockery::mock(AttachmentRepository::class);
        $repo->expects('getFullSizedPaths')
             ->with(123)
             ->andReturn([$this->testDir . '/restore-me.txt'])
             ->once();
        $repo->expects('markAsNonOptimized')
             ->with(123)
             ->once();

        $logger = Mockery::spy(Logger::class);

        $restore = new Restore(
            $repo,
            new Filesystem(),
            $logger
        );

        $restore->execute(123);

        $logger->shouldHaveReceived('section')
               ->with('Restoring full sized images for 1 attachment(s)')
               ->once();
        $logger->shouldHaveReceived('batchOperationResults')
               ->with('full sized image', 'restore', 1, 1, 0)
               ->once();

        $this->filesystem->seeFileFound('restore-me.txt', $this->testDir);
        $this->filesystem->dontSeeFileFound('restore-me.txt.original', $this->testDir);

        $this->filesystem->openFile($this->testDir . '/restore-me.txt');
        $this->filesystem->seeInThisFile('original');
        $this->filesystem->dontSeeInThisFile('optimized');
    }

    public function testRestoreNotExistBackup()
    {
        $repo = Mockery::mock(AttachmentRepository::class);
        $repo->expects('getFullSizedPaths')
             ->with(123)
             ->andReturn([$this->testDir . '/not-exist.png'])
             ->once();
        $repo->expects('markAsNonOptimized')
             ->never();

        $logger = Mockery::spy(Logger::class);

        $restore = new Restore(
            $repo,
            new Filesystem(),
            $logger
        );

        $restore->execute(123);

        $logger->shouldHaveReceived('section')
               ->with('Restoring full sized images for 1 attachment(s)')
               ->once();
        $logger->shouldHaveReceived('batchOperationResults')
               ->with('full sized image', 'restore', 1, 0, 1)
               ->once();

        $this->filesystem->dontSeeFileFound('not-exist.png', $this->testDir);
        $this->filesystem->dontSeeFileFound('not-exist.png.original', $this->testDir);
    }

    public function testRestoreNotAttachment()
    {
        $repo = Mockery::mock(AttachmentRepository::class);
        $repo->expects('getFullSizedPaths')
             ->with(123)
             ->andReturn([])
             ->once();
        $repo->expects('markAsNonOptimized')
             ->never();

        $logger = Mockery::spy(Logger::class);

        $restore = new Restore(
            $repo,
            new Filesystem(),
            $logger
        );

        $restore->execute(123);

        $logger->shouldHaveReceived('section')
               ->with('Restoring full sized images for 1 attachment(s)')
               ->once();
        $logger->shouldHaveReceived('error')
               ->with('Full sized image not found for attachment ID: 123')
               ->once();
        $logger->shouldHaveReceived('batchOperationResults')
               ->with('full sized image', 'restore', 1, 0, 1)
               ->once();
    }

    protected function _before()
    {
        $this->filesystem = $this->getModule('Filesystem');
        $this->testDir = codecept_data_dir('tmp');

        $this->filesystem->copyDir(
            codecept_data_dir('images'),
            $this->testDir
        );

        WP_Mock::userFunction('WP_CLI\Utils\normalize_path')
               ->with(Mockery::type('string'))
               ->andReturnUsing(function ($arg) {
                   return $arg;
               })
               ->zeroOrMoreTimes();
    }

    protected function _after()
    {
        $this->filesystem->deleteDir($this->testDir);
    }
}
