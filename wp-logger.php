<?php
/*
Plugin Name:       WP-Logger
Description:       An actual logger for WordPress.
Plugin URI:        https://github.com/jitesoft/wp-logger
GitHub Plugin URI: https://github.com/jitesoft/wp-logger
Version:           1.4.0
Author:            Johannes Tegnér
Author URI:        https://jitesoft.com
License:           MIT License
*/
require_once dirname(__FILE__) . '/src/init.php';

use Jitesoft\Wordpress\Plugins\Logger\GlobalLogger;
use Psr\Log\LoggerInterface;

Jitesoft\Wordpress\Plugins\Logger\create_jitesoft_logger();

if (function_exists('jitesoft')) {
    Jitesoft\WordPress\Plugins\Base\getContainer()->set('logger', GlobalLogger::logger(), true);
    Jitesoft\WordPress\Plugins\Base\getContainer()->set(LoggerInterface::class, GlobalLogger::logger(), true);
} else {
    GlobalLogger::logger()->info(
        'The jitesoft/base package could not be found. Please autoload for full effect.'
    );
    // Create function!
    function jitesoft(string $moot) {
        return GlobalLogger::logger();
    }
}

if (getenv('WP_LOGGER_OVERRIDE') !== 'disable') {
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
            $logger = jitesoft('logger');

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
}

if (function_exists('do_action')) {
    do_action('jitesoft_logger_loaded', GlobalLogger::logger());
}
