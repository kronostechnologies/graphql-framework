<?php


namespace Kronos\GraphQLFramework\Executor;


use Kronos\GraphQLFramework\FrameworkConfiguration;
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
	 * @param string $typesDirectory
	 */
	protected function configureAutomatedTypesRegistry($typesDirectory)
	{

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

	}
}