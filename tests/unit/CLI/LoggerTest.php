<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface as PsrLoggerInterface;
use TypistTech\ImageOptimizeCommand\LoggerInterface;

class LoggerTest extends Unit
{
    /**
     * @var \TypistTech\ImageOptimizeCommand\UnitTester
     */
    protected $tester;

    public function testImplementsPsrLoggerInterface()
    {
        $logger = new Logger();
        $this->assertInstanceOf(PsrLoggerInterface::class, $logger);
    }

    public function testImplementsLoggerInterface()
    {
        $logger = new Logger();
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
