<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Operations;

use Codeception\Module\Filesystem as FilesystemModule;
use Codeception\Test\Unit;
use Mockery;
use Symfony\Component\Finder\Finder;
use TypistTech\ImageOptimizeCommand\LoggerInterface;
use WP_Mock;

class FindTest extends Unit
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

    public function testFindSuccess()
    {
        $finder = new Finder();
        $logger = Mockery::spy(LoggerInterface::class);
        $find = new Find($finder, $logger);

        $actual = $find->execute($this->testDir, 'png', 'txt', 'original', 'xyz');

        $expected = [
            $this->testDir . '/bot.png',
            $this->testDir . '/bot.png.original',
            $this->testDir . '/find/bot.png',
            $this->testDir . '/find/deep/bot.png',
            $this->testDir . '/restore-me.txt',
            $this->testDir . '/restore-me.txt.original',
        ];
        sort($actual);

        $this->assertSame($expected, $actual);
    }

    public function testFindNotExistDir()
    {
        $finder = new Finder();
        $logger = Mockery::spy(LoggerInterface::class);
        $find = new Find($finder, $logger);

        $actual = $find->execute('/not/exist', 'png', 'txt', 'original', 'xyz');

        $this->assertSame([], $actual);
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
