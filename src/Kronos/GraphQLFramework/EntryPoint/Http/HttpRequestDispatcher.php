<?php


namespace Kronos\GraphQLFramework\EntryPoint\Http;


use HttpException;
use Psr\Http\Message\ServerRequestInterface;

class HttpRequestDispatcher
{
    /**
     * @return HttpRequestHandlerInterface[]
     */
    protected function getHandlers()
    {
        return [
            new GetRequestHandler(),
            new PostRequestHandler()
        ];
    }

    public function dispatch(ServerRequestInterface $request)
    {
        $handlers = $this->getHandlers();

        foreach ($handlers as $handler) {
            if ($handler->canHandle($request)) {
                return $handler->handle($request);
            }
        }

        throw new HttpException("Unsupported method {$request->getMethod()} for GraphQL. Only GET and POST are allowed.", 405);
    }
}