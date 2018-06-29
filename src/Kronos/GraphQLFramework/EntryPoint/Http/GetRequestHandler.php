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
     * @throws MalformedRequestException
     * @throws HttpQueryRequiredException
     * @throws CannotHandleRequestException
     */
    public function handle()
    {
        if (!$this->canHandle()) {
            throw new CannotHandleRequestException($this->request->getMethod());
        }

        $queryString = $this->request->getQueryParams();

        $queryText = $this->getQueryText($queryString);
        $variables = $this->getVariablesArray($queryString);

        return new HandledPayloadResult($queryText, $variables);
    }

    /**
     * @return bool
     */
    public function canHandle()
    {
        return $this->request->getMethod() === 'GET';
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