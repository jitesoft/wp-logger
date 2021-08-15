<?php
/*
Plugin Name:  WP-Logger
Description:  An actual logger for WordPress.
Version:      1.0.0
Author:       Johannes Tegnér
Author URI:   https://jitesoft.com
License:      MIT License
*/
namespace Jitesoft\Wordpress\Plugins\Logger;

require_once dirname(__FILE__) . '/GlobalLogger.php';

$getEnv = static function (string $default = 'production') {
    if (defined('WP_ENV')) {
        return WP_ENV;
    }
    if (getenv('WP_ENV', true)) {
        return getenv('WP_ENV');
    }

    return $default;
};

$environment = $getEnv();
if ($environment === 'production') {
    GlobalLogger::setLogLevel('error');
} else {
    if ($environment === 'staging') {
        GlobalLogger::setLogLevel('info');
    } else {
        GlobalLogger::setLogLevel('debug');
    }
}

set_error_handler(
    static function (
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ) {

        if (!is_string($errstr)) {
            $errstr = json_encode($errstr);
        }

        $output = sprintf('%s - On line %d In %s', $errstr, $errline, $errfile);
        $logger = GlobalLogger::logger();

        switch ($errno) {
            case E_ERROR:
            case E_CORE_ERROR:
                $logger->alert($output);
                break;
            case E_USER_ERROR:
                $logger->error($output);
                break;
            case E_WARNING:
            case E_CORE_WARNING:
            case E_USER_WARNING:
                $logger->warning($output);
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                $logger->notice($output);
                break;
            default:
                $logger->info($output);
                break;
        }
        // We want PHP to handle its own errors. Our handler is just to add our logger to the chain.
        return false;

    }
);
