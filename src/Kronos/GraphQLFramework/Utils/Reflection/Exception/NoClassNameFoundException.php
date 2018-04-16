<?php


namespace Kronos\GraphQLFramework\Utils\Reflection\Exception;


use Kronos\GraphQLFramework\Exception\FrameworkException;
use Throwable;

class NoClassNameFoundException extends FrameworkException
{
    const MSG = 'No class name was found in the active buffer.';

    public function __construct(Throwable $previous = null)
    {
        parent::__construct(self::MSG, 0, $previous);
    }
}