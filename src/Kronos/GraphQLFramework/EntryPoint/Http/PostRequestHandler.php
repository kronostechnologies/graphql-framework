<?php


namespace Kronos\GraphQLFramework\EntryPoint\Http;


use Kronos\GraphQLFramework\EntryPoint\Exception\CannotHandleRequestException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\Exception\MalformedRequestException;
use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Psr\Http\Message\ServerRequestInterface;

class PostRequestHandler implements HttpRequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return HandledPayloadResult
     * @throws CannotHandleRequestException
     * @throws HttpQueryRequiredException
     * @throws MalformedRequestException
     */
    public function handle(ServerRequestInterface $request)
    {
        if (!$this->canHandle($request)) {
            throw new CannotHandleRequestException($request->getMethod());
        }

        $parsedBody = $this->getParsedBodyAsJson($request);

        $queryText = $this->getQueryText($parsedBody);
        $variables = $this->getVariablesArray($parsedBody);

        return new HandledPayloadResult($queryText, $variables);
    }

    /**
     * @param $args
     * @return mixed
     * @throws HttpQueryRequiredException
     */
    protected function getQueryText($args)
    {
        if (!array_key_exists('query', $args)) {
            throw new HttpQueryRequiredException('POST');
        }

        $query = $args['query'];
        return $query;
    }

    /**
     * @param $args
     * @return array|mixed
     */
    protected function getVariablesArray($args)
    {
        $variables = [];

        if (array_key_exists('variables', $args)) {
            $variables = $args['variables'];
        }
        return $variables;
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function canHandle(ServerRequestInterface $request)
    {
        return $request->getMethod() === 'POST';
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     * @throws MalformedRequestException
     */
    protected function getParsedBodyAsJson(ServerRequestInterface $request)
    {
        $parsedBody = @json_decode($request->getBody()->getContents(), true);
        if (!is_array($parsedBody)) {
            throw new MalformedRequestException("query");
        }
        return $parsedBody;
    }
}