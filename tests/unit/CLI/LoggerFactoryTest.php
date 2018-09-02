<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\CLI;

use Codeception\Test\Unit;
use Mockery;
use WP_Mock;

class LoggerFactoryTest extends Unit
{
    /**
     * @var \TypistTech\ImageOptimizeCommand\UnitTester
     */
    protected $tester;

    public function testCreate()
    {
        WP_Mock::userFunction(__NAMESPACE__ . '\apply_filters')
            ->with('typist_tech_image_optimized_logger', Mockery::type(Logger::class))
            ->andReturnUsing(function ($_hook, $arg) {
                return $arg;
            })
            ->once();

        $actual = LoggerFactory::create();

        $expected = new Logger();
        $this->assertEquals($expected, $actual);
    }
}
