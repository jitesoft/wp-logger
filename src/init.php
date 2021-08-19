<?php /** @noinspection PhpUnhandledExceptionInspection */
namespace Jitesoft\Wordpress\Plugins\Logger;

require_once dirname(__FILE__) . '/GlobalLogger.php';

function create_jitesoft_logger() {
    function get_wp_env (string $default = 'production') {
        if (defined('WP_ENV')) {
            return WP_ENV;
        }
        if (getenv('WP_ENV')) {
            return getenv('WP_ENV');
        }

        return $default;
    }

    if (!getenv('WP_LOGGER_LEVEL')) {
        $environment = get_wp_env('production');
        if ($environment === 'production') {
            GlobalLogger::setLogLevel('error');
        } else {
            if ($environment === 'staging') {
                GlobalLogger::setLogLevel('info');
            } else {
                GlobalLogger::setLogLevel('debug');
            }
        }
    } else {
        GlobalLogger::setLogLevel(getenv('WP_LOGGER_LEVEL'));
    }

    add_action('jitesoft_log_level', static function (string $level) {
       GlobalLogger::setLogLevel($level);
    });
}
