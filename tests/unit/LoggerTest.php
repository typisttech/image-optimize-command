<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;

class LoggerTest extends Unit
{
    /**
     * @var \TypistTech\ImageOptimizeCommand\UnitTester
     */
    protected $tester;

    // tests
    public function testImplementsPsrLoggerInterface()
    {
        $logger = new Logger();
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
