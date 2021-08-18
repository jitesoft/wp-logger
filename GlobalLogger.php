<?php
namespace Jitesoft\Wordpress\Plugins\Logger;

use Jitesoft\Log\JsonLogger;
use Jitesoft\Log\MultiLogger;
use Jitesoft\Log\StdLogger;
use Psr\Log\LoggerInterface;

/**
 * A global logger initialized as a singleton (statically).
 * A new logger is created if there is none created.
 * Currently uses the STDLogger only, which logs to stdout and stderr.
 */
class GlobalLogger {
    /**
     * Set the log level to use.
     * Automatically done on plugin initialization.
     *
     * @param string $level
     */
    public static function setLogLevel(string $level): void {
        self::logger()->setLogLevel($level);
    }

    /**
     * Get the logger.
     *
     * @return LoggerInterface|MultiLogger
     */
    public static function logger(): LoggerInterface {
        static $logger = null;
        if (!$logger) {

            $format = getenv('WP_LOGGER_FORMAT');
            if (!$format) {
                $format = 'stdout';
            }

            $outLogger = null;
            if ($format === 'json') {
                $outLogger = new JsonLogger();
            } else {
                $outLogger = new StdLogger(
                    StdLogger::DEFAULT_FORMAT,
                    "Y-m-d H:i:s.v"
                );
            }

            $logger = new MultiLogger([
                $outLogger,
            ]);
        }
        return $logger;
    }

}
