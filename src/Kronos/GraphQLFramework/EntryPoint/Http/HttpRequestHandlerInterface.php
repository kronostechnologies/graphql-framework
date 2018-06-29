<?php


namespace Kronos\GraphQLFramework\EntryPoint\Http;


use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Psr\Http\Message\ServerRequestInterface;

interface HttpRequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return HandledPayloadResult
     */
    public function handle(ServerRequestInterface $request);

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function canHandle(ServerRequestInterface $request);
}