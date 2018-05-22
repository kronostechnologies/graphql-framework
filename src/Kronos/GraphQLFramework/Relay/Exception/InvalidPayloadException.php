<?php


namespace Kronos\GraphQLFramework\Relay\Exception;



use Kronos\GraphQLFramework\Exception\FrameworkException;

class InvalidPayloadException extends FrameworkException
{
    const INVALID_B64_CODE = 1;
    const INVALID_B64_MSG = 'Invalid base 64 string given.';

    const INVALID_JSON_CODE = 2;
    const INVALID_JSON_MSG = 'Malformed JSON passed to handler: fields missing.';

    const INVALID_INDEX_TYPE_CODE = 3;
    const INVALID_INDEX_TYPE_MSG = 'Number expected for index field.';

    const INVALID_ARG_CODE = 4;
    const INVALID_ARG_MSG = 'Invalid argument type given.';

    /**
     * @param int $code
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->setMessageFromCode();
    }

    protected function setMessageFromCode()
    {
        if ($this->code === self::INVALID_B64_CODE) {
            $this->message = self::INVALID_B64_MSG;
        }

        if ($this->code === self::INVALID_JSON_CODE) {
            $this->message = self::INVALID_JSON_MSG;
        }

        if ($this->code === self::INVALID_INDEX_TYPE_CODE) {
            $this->message = self::INVALID_INDEX_TYPE_MSG;
        }

        if ($this->code === self::INVALID_ARG_CODE) {
            $this->message = self::INVALID_ARG_MSG;
        }
    }
}
