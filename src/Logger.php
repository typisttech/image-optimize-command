<?php

declare(strict_types=1);

namespace TypistTech\ImageOptimizeCommand;

use Psr\Log\LoggerInterface;
use WP_CLI;

class Logger implements LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @throws WP_CLI\ExitException Exit script.
     *
     * @return void
     */
    public function emergency($message, array $_context = [])
    {
        WP_CLI::error($message);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @throws WP_CLI\ExitException Exit script.
     *
     * @return void
     */
    public function alert($message, array $_context = [])
    {
        WP_CLI::error($message);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @throws WP_CLI\ExitException Exit script.
     *
     * @return void
     */
    public function critical($message, array $_context = [])
    {
        WP_CLI::error($message);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @throws WP_CLI\ExitException Exit script.
     *
     * @return void
     */
    public function error($message, array $_context = [])
    {
        WP_CLI::error($message);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @return void
     */
    public function warning($message, array $_context = [])
    {
        WP_CLI::warning($message);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @return void
     */
    public function notice($message, array $_context = [])
    {
        WP_CLI::success($message);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @return void
     */
    public function info($message, array $_context = [])
    {
        WP_CLI::log($message);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @return void
     */
    public function debug($message, array $_context = [])
    {
        WP_CLI::debug($message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level    The level.
     * @param string $message  The message.
     * @param array  $_context The context.
     *
     * @return void
     */
    public function log($level, $message, array $_context = [])
    {
        WP_CLI::log('[' . $level . ']' . $message);
    }
}
