<?php


namespace Kronos\GraphQLFramework\EntryPoint;


use function array_key_exists;
use GuzzleHttp\Psr7\Response;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpVariablesIncorrectlyDefinedException;
use Kronos\GraphQLFramework\Executor\Executor;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Psr\Http\Message\ResponseInterface;
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

	/**
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 * @throws \HttpException
	 * @throws HttpQueryRequiredException
	 * @throws HttpVariablesIncorrectlyDefinedException
	 */
    public function executeRequest(ServerRequestInterface $request)
    {
        if ($request->getMethod() === 'GET') {
            return $this->executeGetRequest($request);
        } else if ($request->getMethod() === 'POST') {
            return $this->executePostRequest($request);
        } else {
            throw new \HttpException("Unsupported method {$request->getMethod()} for GraphQL. Only GET and POST are allowed.", 405);
        }
    }

	/**
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 * @throws HttpQueryRequiredException
	 * @throws HttpVariablesIncorrectlyDefinedException
	 */
    protected function executeGetRequest(ServerRequestInterface $request)
    {
        $queryParams = $request->getQueryParams();

        if (!array_key_exists('query', $queryParams)) {
        	throw new HttpQueryRequiredException('GET');
		}

        $variables = array_key_exists('variables', $queryParams) ? $queryParams['variables'] : null;
		$query = $queryParams['query'];

		if (strpos($query, "query ") !== 0) {
			$query = "query " . $query;
		}

		$areVariablesSet = ($variables !== null && trim($variables) !== "");

		if ($areVariablesSet) {
			$variables = json_decode($variables, true);

			if ($variables === null) {
				throw new HttpVariablesIncorrectlyDefinedException('GET');
			}
		} else {
			$variables = [];
		}

		return $this->executeQueryAndGetResponse($query, $variables);
    }

	/**
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 * @throws HttpQueryRequiredException
	 * @throws HttpVariablesIncorrectlyDefinedException
	 */
    protected function executePostRequest(ServerRequestInterface $request)
    {
        $parsedBody = json_decode($request->getBody()->getContents(), true);

		if ($parsedBody === null || !array_key_exists('query', $parsedBody)) {
			throw new HttpQueryRequiredException('POST');
		}

        $variables = array_key_exists('variables', $parsedBody) ? $parsedBody['variables'] : null;
        $query = $parsedBody['query'];

        $areVariablesSet = ($variables !== null && trim($variables) !== "");

        if ($areVariablesSet) {
			$variables = json_decode($variables, true);

			if ($variables === null) {
				throw new HttpVariablesIncorrectlyDefinedException('POST');
			}
		} else {
        	$variables = [];
		}

		return $this->executeQueryAndGetResponse($query, $variables);
    }

	/**
	 * Useful for mocking.
	 *
	 * @return Executor
	 */
    protected function getExecutor()
	{
		return new Executor($this->configuration);
	}

	/**
	 * @param string $queryString
	 * @param array $variables
	 * @return ResponseInterface
	 */
	protected function executeQueryAndGetResponse($queryString, array $variables)
	{
		$executor = $this->getExecutor();

		$result = $executor->executeQuery($queryString, $variables);

		$statusCode = ($result->hasError() ? 500 : 200);
		$headers = [];

		return new Response($statusCode, $headers, $result->getResponseText());
	}
}