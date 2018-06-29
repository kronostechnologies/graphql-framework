<?php


namespace Kronos\GraphQLFramework\EntryPoint\Http;


use Kronos\GraphQLFramework\EntryPoint\Exception\CannotHandleRequestException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\Exception\MalformedRequestException;
use Kronos\GraphQLFramework\EntryPoint\HandledPayloadResult;
use Psr\Http\Message\ServerRequestInterface;

class GetRequestHandler implements HttpRequestHandlerInterface
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

        $queryString = $request->getQueryParams();

        $queryText = $this->getQueryText($queryString);
        $variables = $this->getVariablesArray($queryString);

        return new HandledPayloadResult($queryText, $variables);
    }

    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function canHandle(ServerRequestInterface $request)
    {
        return $request->getMethod() === 'GET';
    }

    /**
     * @param $queryString
     * @return mixed
     * @throws HttpQueryRequiredException
     */
    protected function getQueryText($queryString)
    {
        if (!array_key_exists('query', $queryString)) {
            throw new HttpQueryRequiredException('GET');
        }

        $query = $queryString['query'];
        return $query;
    }

    /**
     * @param $queryString
     * @return array|mixed
     * @throws MalformedRequestException
     */
    protected function getVariablesArray($queryString)
    {
        $variables = [];

        if (array_key_exists('variables', $queryString)) {
            $variables = @json_decode($queryString['variables'], true);

            if (!is_array($variables)) {
                throw new MalformedRequestException('variables');
            }
        }
        return $variables;
    }
}