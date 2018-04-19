<?php


namespace Kronos\GraphQLFramework\Utils\Reflection;


use Kronos\GraphQLFramework\Utils\Reflection\Exception\NoClassMethodFoundException;
use ReflectionClass;
use ReflectionMethod;

class ClassMethodsReader
{
	/**
	 * @var string
	 */
	protected $classFQN;

	/**
	 * @var array
	 */
	protected $knownMethods;

	/**
	 * @param string $classFQN
	 */
	public function __construct($classFQN)
	{
		$this->classFQN = $classFQN;
	}

	/**
	 * Returns an array of 'lowercasemethod' => 'lowercaseMethod' which corresponds to '(LowercasedMethodName)' => '(ActualMethodName)'.
	 * Only public methods are returned.
	 *
	 * @return string[]
	 * @throws \ReflectionException
	 */
	public function getLowercaseMethodsAssociations()
	{
		if ($this->knownMethods === null) {
			$reflectionClass = new ReflectionClass($this->classFQN);
			$methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

			$this->knownMethods = [];
			foreach ($methods as $method) {
				$this->knownMethods[strtolower($method)] = $method;
			}
		}

		return $this->knownMethods;
	}

	/**
	 * @param string $soughtName
	 * @return string
	 * @throws \ReflectionException
	 * @throws NoClassMethodFoundException
	 */
	public function getMethodForName($soughtName)
	{
		$lowercaseSoughtName = strtolower($soughtName);

		$knownMethods = $this->getLowercaseMethodsAssociations();

		foreach ($knownMethods as $lowercasedKnownMethod => $knownMethod) {
			if ($lowercasedKnownMethod === $lowercaseSoughtName) {
				return $knownMethod;
			}
		}

		throw new NoClassMethodFoundException($soughtName, $this->classFQN);
	}
}