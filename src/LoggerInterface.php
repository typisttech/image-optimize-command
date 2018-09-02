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

    /**
     * Report the results of the same operation against multiple resources.
     *
     * @param string       $noun      Resource being affected (e.g. plugin).
     * @param string       $verb      Type of action happening to the noun (e.g. activate).
     * @param integer      $total     Total number of resource being affected.
     * @param integer      $successes Number of successful operations.
     * @param integer      $failures  Number of failures.
     * @param null|integer $skips     Optional. Number of skipped operations. Default null (don't show skips).
     *
     * @return void
     */
    public function batchOperationResults(
        string $noun,
        string $verb,
        int $total,
        int $successes,
        int $failures,
        ?int $skips = null
    ): void;
}
