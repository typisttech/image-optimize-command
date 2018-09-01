<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations;

use Codeception\Module\Filesystem as FilesystemModule;
use Mockery;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\Logger;
use WP_Mock;

class BackupTest extends \Codeception\Test\Unit
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

    protected function _before()
    {
        $this->filesystem = $this->getModule('Filesystem');
        $this->testDir = codecept_data_dir('test-images');

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

    public function testBackupSuccess()
    {
        $logger = Mockery::spy(Logger::class);

        $backup = new Backup(
            new Filesystem(),
            $logger
        );

        $backup->execute($this->testDir . '/tangrufus.jpeg');

        $this->filesystem->seeFileFound('tangrufus.jpeg', $this->testDir);
        $this->filesystem->seeFileFound('tangrufus.jpeg.original', $this->testDir);
        $logger->shouldHaveReceived('section')
               ->with('Backing up 1 file(s)')
               ->once();
        $logger->shouldHaveReceived('batchOperationResults')
               ->with('file', 'backup', 1, 1, 0, 0)
               ->once();
    }

    public function testBackupOriginalNotExist()
    {
        $logger = Mockery::spy(Logger::class);

        $backup = new Backup(
            new Filesystem(),
            $logger
        );

        $backup->execute($this->testDir . '/not-exist.jpeg');

        $this->filesystem->dontSeeFileFound('not-exist.jpeg', $this->testDir);
        $this->filesystem->dontSeeFileFound('not-exist.jpeg.original', $this->testDir);
        $logger->shouldHaveReceived('section')
               ->with('Backing up 1 file(s)')
               ->once();
        $logger->shouldHaveReceived('batchOperationResults')
               ->with('file', 'backup', 1, 0, 1, 0)
               ->once();
    }

    public function testBackupAlreadyExist()
    {
        $logger = Mockery::spy(Logger::class);

        $backup = new Backup(
            new Filesystem(),
            $logger
        );

        $backup->execute($this->testDir . '/bot.png');

        $this->filesystem->seeFileFound('bot.png', $this->testDir);
        $this->filesystem->seeFileFound('bot.png.original', $this->testDir);
        $logger->shouldHaveReceived('section')
               ->with('Backing up 1 file(s)')
               ->once();
        $logger->shouldHaveReceived('batchOperationResults')
               ->with('file', 'backup', 1, 0, 0, 1)
               ->once();
    }

    public function testBackup()
    {
        $logger = Mockery::spy(Logger::class);

        $backup = new Backup(
            new Filesystem(),
            $logger
        );

        $backup->execute(
            $this->testDir . '/bot.png',
            $this->testDir . '/not-exist.jpeg',
            $this->testDir . '/tangrufus.jpeg'
        );

        $this->filesystem->seeFileFound('bot.png', $this->testDir);
        $this->filesystem->seeFileFound('bot.png.original', $this->testDir);
        $this->filesystem->dontSeeFileFound('not-exist.jpeg', $this->testDir);
        $this->filesystem->dontSeeFileFound('not-exist.jpeg.original', $this->testDir);
        $this->filesystem->seeFileFound('tangrufus.jpeg', $this->testDir);
        $this->filesystem->seeFileFound('tangrufus.jpeg.original', $this->testDir);
        $logger->shouldHaveReceived('section')
               ->with('Backing up 3 file(s)')
               ->once();
        $logger->shouldHaveReceived('batchOperationResults')
               ->with('file', 'backup', 3, 1, 1, 1)
               ->once();
    }
}
