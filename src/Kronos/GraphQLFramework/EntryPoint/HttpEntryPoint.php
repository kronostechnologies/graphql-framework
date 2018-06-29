<?php


namespace Kronos\GraphQLFramework\EntryPoint;


use GuzzleHttp\Psr7\Response;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpQueryRequiredException;
use Kronos\GraphQLFramework\EntryPoint\Exception\HttpVariablesIncorrectlyDefinedException;
use Kronos\GraphQLFramework\EntryPoint\Http\GetRequestHandler;
use Kronos\GraphQLFramework\EntryPoint\Http\HttpRequestDispatcher;
use Kronos\GraphQLFramework\EntryPoint\Http\HttpRequestHandlerInterface;
use Kronos\GraphQLFramework\EntryPoint\Http\PostRequestHandler;
use Kronos\GraphQLFramework\Exception\ClientDisplayableExceptionInterface;
use Kronos\GraphQLFramework\Executor\Executor;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function array_key_exists;

class HttpEntryPoint
{
    /**
     * @var FrameworkConfiguration
     */
    protected $configuration;

    /**
     * @var HttpRequestDispatcher
     */
    protected $dispatcher;

    /**
     * @var Executor
     */
    protected $executor;

    /**
     * @param FrameworkConfiguration $configuration
     * @param HttpRequestDispatcher|null $dispatcher
     */
    public function __construct(FrameworkConfiguration $configuration, HttpRequestDispatcher $dispatcher = null, Executor $executor = null)
    {
        $this->configuration = $configuration;
        $this->dispatcher = $dispatcher ?: new HttpRequestDispatcher();
        $this->executor = $executor ?: new Executor();
    }

	/**
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 */
    public function executeRequest(ServerRequestInterface $request)
    {
        try {
            $result = $this->dispatcher->dispatch($request);
        } catch (\Exception $ex) {
            return new Response(405, [], $ex->getMessage());
        }

        return $this->executeQueryAndGetResponse($result->getQuery(), $result->getVariables());
    }

	/**
	 * @param string $queryString
	 * @param array $variablesf
	 * @return ResponseInterface
	 */
	protected function executeQueryAndGetResponse($queryString, array $variables)
	{
	    $this->executor->configure($this->configuration);

		$result = $this->executor->executeQuery($queryString, $variables);

		if ($result->hasError()) {
			$underlyingException = $result->getUnderlyingException();
			if ($underlyingException instanceof ClientDisplayableExceptionInterface) {
				$statusCode = $underlyingException->getClientHttpStatusCode();
			} else {
				$statusCode = 500;
			}
		} else {
			$statusCode = 200;
		}
		$headers = [];

		return new Response($statusCode, $headers, $result->getResponseText());
    }
}
