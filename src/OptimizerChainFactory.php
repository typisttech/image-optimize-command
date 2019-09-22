<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\OptimizerChainFactory as BaseOptimizerChainFactory;

class OptimizerChainFactory
{
    public const HOOK = 'TypistTech/ImageOptimizeCommand/OptimizerChain';

    /**
     * Creates an spatie/image-optimizer optimizer chain.
     *
     * @return OptimizerChain
     */
    public static function create(): OptimizerChain
    {
        return apply_filters(
            static::HOOK,
            BaseOptimizerChainFactory::create()
        );
    }
}
