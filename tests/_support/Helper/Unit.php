<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module;
use Codeception\TestInterface;
use Mockery;
use WP_Mock;

class Unit extends Module
{
    public function _before(TestInterface $test)
    {
        WP_Mock::setUp();
    }

    public function _after(TestInterface $test)
    {
        WP_Mock::tearDown();
        Mockery::close();
    }
}
