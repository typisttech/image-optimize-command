<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use Codeception\Test\Unit;
use Mockery;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\OptimizerChainFactory as BaseOptimizerChainFactory;
use WP_Mock;

class OptimizerChainFactoryTest extends Unit
{
    public function testCreate()
    {
        $expected = BaseOptimizerChainFactory::create();

        WP_Mock::userFunction(__NAMESPACE__ . '\apply_filters')
               ->with(
                   OptimizerChainFactory::HOOK,
                   Mockery::type(OptimizerChain::class)
               )
               ->andReturnUsing(function ($_hook, $arg) {
                   return $arg;
               })
               ->once();

        $actual = OptimizerChainFactory::create();

        $this->assertEquals($expected, $actual);
    }
}
