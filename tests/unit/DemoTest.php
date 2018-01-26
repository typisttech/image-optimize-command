<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

class DemoTest extends \Codeception\Test\Unit
{
    /**
     * @var \TypistTech\ImageOptimizeCommand\UnitTester
     */
    protected $tester;

    /**
     * @test
     */
    public function it_is_the_truth()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_is_false()
    {
        $this->assertFalse(false);
    }
}
