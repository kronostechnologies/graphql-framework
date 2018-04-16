<?php


namespace Kronos\GraphQLFramework\Exception;


use Throwable;

class NoCacheAdapterConfiguredException extends FrameworkException
{
    const MSG = 'Your configuration specifically FORCES cache %cacheName% to be enabled, but no CacheAdapter is configured. Please set it correctly with $configuration->setCacheAdapter(...) or don\'t force it.';

    public function __construct($cacheName, Throwable $previous = null)
    {
        $message = str_replace("%cacheName%", $cacheName, self::MSG);

        parent::__construct($message, 0, $previous);
    }
}