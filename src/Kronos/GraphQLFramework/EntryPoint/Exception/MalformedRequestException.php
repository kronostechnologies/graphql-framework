<?php


namespace Kronos\GraphQLFramework\EntryPoint\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class MalformedRequestException extends FrameworkException
{
    /**
     * @param string $fieldName
     * @param Throwable|null $previous
     */
    public function __construct($fieldName, Throwable $previous = null)
    {
        $message = "Malformed request. Field {$fieldName} could not be processed correctly.";

        parent::__construct($message, $previous);
    }
}