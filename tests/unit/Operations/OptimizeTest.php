<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations;

use Codeception\Module\Filesystem as FilesystemModule;
use Codeception\Test\Unit;
use Mockery;
use Spatie\ImageOptimizer\OptimizerChain;
use Symfony\Component\Filesystem\Filesystem;
use TypistTech\ImageOptimizeCommand\CLI\Logger;
use WP_Mock;

class OptimizeTest extends Unit
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

    public function testOptimizeSuccess()
    {
        $path = $this->testDir . '/tangrufus.jpeg';

        $optimizerChain = Mockery::mock(OptimizerChain::class);
        $optimizerChain->expects('optimize')
                       ->with($path)
                       ->once();

        $logger = Mockery::spy(Logger::class);

        $optimize = new Optimize(
            $optimizerChain,
            new Filesystem(),
            $logger
        );

        $optimize->execute($path);

        $logger->shouldHaveReceived('debug')
               ->with('Optimizing image - ' . $path)
               ->once();
    }

    public function testOptimizeNotExist()
    {
        $path = $this->testDir . '/not-exist.jpeg';

        $optimizerChain = Mockery::mock(OptimizerChain::class);
        $optimizerChain->expects('optimize')
                       ->never();

        $logger = Mockery::spy(Logger::class);

        $optimize = new Optimize(
            $optimizerChain,
            new Filesystem(),
            $logger
        );

        $optimize->execute($path);

        $logger->shouldHaveReceived('error')
               ->with('Image not exist - ' . $path)
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
