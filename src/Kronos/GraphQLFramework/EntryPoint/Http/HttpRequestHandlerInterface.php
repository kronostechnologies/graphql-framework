<?php


namespace Kronos\GraphQLFramework\EntryPoint\Http;


use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;

interface HttpRequestHandlerInterface
{
    /**
     * @return HandledPayloadResult
     */
    public function handle();

    /**
     * @return bool
     */
    public function canHandle();
}