<?php
declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use Psr\Log\LoggerInterface as BaseLoggerInterface;

interface LoggerInterface extends BaseLoggerInterface
{
    /**
     * Section header.
     *
     * @param string $title The section title.
     *
     * @return void
     */
    public function section($title): void;
}
