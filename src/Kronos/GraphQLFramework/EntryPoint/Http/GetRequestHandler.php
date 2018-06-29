<?php


namespace Kronos\GraphQLFramework\EntryPoint\Http;


use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Psr\Http\Message\ServerRequestInterface;

class GetRequestHandler implements HttpRequestHandlerInterface
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * GetRequestHandler constructor.
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return HandledPayloadResult
     */
    public function handle()
    {
        // TODO: Implement handle() method.
    }

    /**
     * @return bool
     */
    public function canHandle()
    {
        // TODO: Implement canHandle() method.
    }
}