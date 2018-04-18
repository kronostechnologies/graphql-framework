<?php


namespace Kronos\GraphQLFramework;


use Kronos\GraphQLFramework\TypeRegistry\AutomatedTypeRegistry;

class Executor
{
	/**
	 * @var string
	 */
	protected $queryString;

	/**
	 * @var array
	 */
	protected $arguments;

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
	 * @param string $queryString
	 * @param array $arguments
	 * @param AutomatedTypeRegistry|null $customTypeRegistry Only used for mocking currently.
	 */
	public function __construct(FrameworkConfiguration $configuration, $queryString, array $arguments, AutomatedTypeRegistry $customTypeRegistry = null)
	{
		$this->configuration = $configuration;
		$this->queryString = $queryString;
		$this->arguments = $arguments;

		$this->typeRegistry = $customTypeRegistry ?: new AutomatedTypeRegistry();
	}
}