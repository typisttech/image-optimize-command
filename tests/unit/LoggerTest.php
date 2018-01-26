<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;

class LoggerTest extends Unit
{
    /** @test */
    public function it_is_an_instance_of_logger_interface()
    {
        $logger = new Logger();
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
