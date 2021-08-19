# Wp-logger

## An actual logger for WordPress.

This plugin does two things. It piggybacks on the internal php logger (via `set_error_handler`) and 
it creates a PSR logger for you to use!

### More in-depth

When the plugin have loaded completely in WordPress, it will fire the `jitesoft_logger_loaded` action,
the callback will be passed the global logger object.  
If you _know_ that the plugin is loaded, you can as well use the `jitesoft('logger')` function to request the logger
instance. In case it is not yet loaded, the function will return null:

```php 
$logger = jitesoft('logger'); // null if not lodaed
add_action('jitesoft_logger_loaded', static fn($logger) => $logger); // will not be null when loaded.
```

_Deprecation notice, the following will be removed in next major version_

It's also possible to fetch the logger through the `GlobalLogger` class through the `logger()` method:

```php 
Jitesoft\WordPress\Plugins\Logger\GlobalLogger;

$logger = GlobalLogger::logger();
```

### Auto-loading

The plugin requires auto-loading to function properly. Without the autoloader, the plugin won't be able to find
the libraries it uses and hence will not create a logger.

## Configuring

The plugin is currently configured via environment variables or defines. The values must be
defined _before_ loading the plugin, so the wp-config.php file or actual environment variables are preferable
places to put those.

`WP_LOGGER_OVERRIDE` is default set to override, can be changed to `disable` to _not_ override the internal
php logger.  
  
`WP_ENV` sets the default logging level on the logger:

  * `production`: errors and above
  * `staging`: info and above
  * `development`: debug and above

In case you wish to change the logging level manually, you can set the `WP_LOGGER_LEVEL` to an appropriate level,
the following are accepted: `debug`, `notice`, `info`, `warning`, `error`, `critical`, `alert`, `emergency`.

You can also change the log level by fireing the `jitesoft_log_level` hook with a single string parameter
with a value of the above log levels.

### Formatting

Currently, the logger supports two default types: `json`, `stdout`.   
They are possible to set with the `WP_LOGGER_FORMAT` variable (`stdout` is default).  
Both of the types will output to the stdout/stderr channel (the terminal) while they 
will produce either clear text logs or json formatted logs.  
  
If you wish to change the loggers on the logging object, it's possible to do so by querying the logger and
add (or remove) loggers from it. Multiple loggers can be added:

```php 
jitesoft('logger')->removeLogger('stdout');
jitesoft('logger')->removeLogger('json');

jitesoft('logger')->addLogger(new MyPsrLogger(), 'myLogger');
```

Or directly in the hook:

```php 
add_action('jitesoft_logger_loaded', static function($logger) {
    $logger->removeLogger('stdout');
    $logger->removeLogger('json');
    $logger->addLogger(new MyPsrLogger(), 'myLogger');
});
```

## Installation

Use a composer based WordPress installation and require this plugin.  

## Loggers

If you wish to add more loggers, the package depends on the [`jitesoft/loggers`](https://packagist.org/packages/jitesoft/loggers)
php package, which contains multiple different loggers.  
If you wish to use your own loggers, the logger must implement the [PSR-3 logger](https://www.php-fig.org/psr/psr-3/) interface.

## License

MIT!
