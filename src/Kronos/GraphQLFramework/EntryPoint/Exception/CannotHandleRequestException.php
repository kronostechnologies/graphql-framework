<?php


namespace Kronos\GraphQLFramework\EntryPoint\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class CannotHandleRequestException extends FrameworkException
{
    /**
     * @param string $method POST or GET
     * @param Throwable|null $previous
     */
    public function __construct($method, Throwable $previous = null)
    {
        $message = "Cannot handle HTTP request with method '{$method}'.";

        parent::__construct($message, $previous);
    }
}