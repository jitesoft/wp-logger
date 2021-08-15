# Wp-logger

### An actual logger for WordPress.

Plugin which overrides the default php error handling with a step in which a PSR logger is
invoked to output logs in other formats than the default php error log.  
Further, the plugin contains a `GlobalLogger` class with a static `logger()` method to allow
user defined logging to be made with the same configuration.  
Depending on the WP_ENV define or env variable, different log levels will be enabled.  

Intended to be used as a MU-plugin.

## Installation

Use a composer based WordPress installation and require this plugin.  

## Usage

To use the logger in your own code, just call the `Jitesoft\Wordpress\Plugins\Logger\GlobalLogger::logger()` object,
which will return a PSR logger to use!  
  
The PSR Logger in the implementation currently only uses a STD logger (stdout and stderr) but
it's a "multi logger" (see [jitesoft/logger](https://packagist.org/packages/jitesoft/loggers) for more information) which allow you to add more loggers
to it if needed.

In future versions, more loggers might be added.

## License

MIT!
