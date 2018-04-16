<?php


namespace Kronos\GraphQLFramework\EntryPoint;


use Kronos\GraphQLFramework\FrameworkConfiguration;
use Psr\Http\Message\ServerRequestInterface;

class HttpEntryPoint
{
    /**
     * @var FrameworkConfiguration
     */
    protected $configuration;

    public function __construct(FrameworkConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function executeRequest(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'GET') {
            $this->executeGetRequest($request);
        } else if ($request->getMethod() === 'POST') {
            $this->executePostRequest($request);
        } else {
            throw new \HttpException("Unsupported method {$request->getMethod()} for GraphQL. Only GET and POST are allowed.", 405);
        }
    }

    protected function executeGetRequest(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        $variables = $queryParams['variables'];
        $query = $queryParams['body'];
    }

    protected function executePostRequest(ServerRequestInterface $request)
    {
        $parsedBody = $request->getParsedBody();

        $variables = $parsedBody['variables'];
        $query = $parsedBody['query'];
    }
}