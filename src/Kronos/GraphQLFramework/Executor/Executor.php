<?php


namespace Kronos\GraphQLFramework\Executor;


use GraphQL\Error\Debug;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Kronos\GraphQLFramework\Exception\ClientDisplayableExceptionInterface;
use Kronos\GraphQLFramework\FrameworkConfiguration;
use Kronos\GraphQLFramework\Resolver\Resolver;
use Kronos\GraphQLFramework\TypeRegistry\Automated\GeneratedSchemaDefinition;
use Kronos\GraphQLFramework\TypeRegistry\AutomatedTypeRegistry;

class Executor
{
	/**
	 * @var FrameworkConfiguration
	 */
	protected $configuration;

	/**
	 * @var AutomatedTypeRegistry
	 */
	protected $typeRegistry;

	/**
	 * @var Schema
	 */
	protected $schema;

	/**
	 * @param FrameworkConfiguration $configuration
	 * @param AutomatedTypeRegistry|null $customTypeRegistry Only used for mocking currently.
	 */
	public function __construct(FrameworkConfiguration $configuration, $customTypeRegistry = null)
	{
		$this->configuration = $configuration;

		if ($customTypeRegistry === null) {
			$this->configureAutomatedTypesRegistry($this->configuration->getGeneratedSchemaDirectory());
		} else {
			$this->typeRegistry = $customTypeRegistry;
		}
	}

	/**
	 * @param string $schemaDirectory
	 */
	protected function configureAutomatedTypesRegistry($schemaDirectory)
	{
		$schemaDefinition = new GeneratedSchemaDefinition($schemaDirectory);
		$typesDirectory = $schemaDefinition->getTypesDirectory();
		$resolver = new Resolver($this->configuration);

		$this->typeRegistry = new AutomatedTypeRegistry($resolver, $typesDirectory);
	}

	protected function loadSchema()
	{
		if ($this->schema === null) {
			$this->schema = new Schema([
				'query' => $this->typeRegistry->getQueryType(),
				'mutation' => $this->typeRegistry->getMutationType()
			]);
		}
	}

	/**
	 * Executes a query and returns its results.
	 *
	 * @param string $queryString
	 * @param array $variables
	 * @return ExecutorResult
	 */
	public function executeQuery($queryString, array $variables)
	{
        try {
            $this->loadSchema();

            $resolversResult = GraphQL::executeQuery(
                $this->schema,
                $queryString,
                null,
                null,
                $variables
            );
        } catch (\Exception $ex) {
            if ($this->configuration->isDevModeEnabled()) {
                return new ExecutorResult(json_encode([
                    'internalException' => [
                        'message' => $ex->getMessage(),
                        'trace' => $ex->getTrace(),
                    ]
                ]), $ex);
            } else {
            	if ($ex instanceof ClientDisplayableExceptionInterface) {
					$exceptionPayload = [ 'error' => [
						'code' => $ex->getClientErrorCode(),
						'description' => $ex->getClientErrorDescription(),
						'statusCode' => $ex->getClientHttpStatusCode(),
					]];
				} else {
					$exceptionPayload = [ 'error' => 'An internal error has occured' ];
				}

				if ($this->configuration->getExceptionHandler() !== null) {
            		$exceptionHandler = $this->configuration->getExceptionHandler();
					$exceptionHandler($ex);
				}

                return new ExecutorResult(json_encode($exceptionPayload), $ex);
            }
        }

        if ($this->configuration->isDevModeEnabled()) {
            return new ExecutorResult(json_encode($resolversResult->toArray(Debug::INCLUDE_DEBUG_MESSAGE | Debug::INCLUDE_TRACE)));
        } else {
            return new ExecutorResult(json_encode($resolversResult->toArray()));
        }
	}
}
