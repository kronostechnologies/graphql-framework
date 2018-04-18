<?php


namespace Kronos\GraphQLFramework\Executor;


use Exception;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Kronos\GraphQLFramework\FrameworkConfiguration;
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
	public function __construct(FrameworkConfiguration $configuration, AutomatedTypeRegistry $customTypeRegistry = null)
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

		$this->typeRegistry = new AutomatedTypeRegistry($typesDirectory);
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
		$this->loadSchema();

		try {
			$resolversResult = GraphQL::executeQuery(
				$this->schema,
				$queryString,
				null,
				null,
				$variables
			);

			return new ExecutorResult(json_encode($resolversResult->jsonSerialize()));
		} catch (Exception $ex) {
			return new ExecutorResult("", $ex);
		}
	}
}