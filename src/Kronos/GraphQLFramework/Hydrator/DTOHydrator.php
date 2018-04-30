<?php


namespace Kronos\GraphQLFramework\Hydrator;


use ArgumentCountError;
use Kronos\GraphQLFramework\Hydrator\Definition\BaseDTODefinition;
use Kronos\GraphQLFramework\Hydrator\Exception\DTORequiresArgumentsException;
use ReflectionClass;
use ReflectionProperty;
use function array_key_exists;

class DTOHydrator
{
	/**
	 * @param string $className
	 * @param array $values
	 * @return mixed
	 * @throws \ReflectionException
	 * @throws DTORequiresArgumentsException
	 */
	public function fromSimpleArray($className, array $values)
	{
		$instance = $this->createDTOInstance($className);
		$this->setPropertiesInInstance($instance, $values);

		return $instance;
	}

	public function fromBaseDTODefinition(BaseDTODefinition $definition, array $values)
	{

	}

	/**
	 * @param string $fqn
	 * @return mixed
	 * @throws DTORequiresArgumentsException
	 */
	protected function createDTOInstance($fqn)
	{
		try {
			return new $fqn();
		} catch (ArgumentCountError $ex) {
			throw new DTORequiresArgumentsException($fqn);
		}
	}

	/**
	 * @param mixed $instance
	 * @param array $propertiesValuesArray
	 * @throws \ReflectionException
	 */
	protected function setPropertiesInInstance(&$instance, array $propertiesValuesArray)
	{
		$reflectionClass = new ReflectionClass($instance);
		$properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

		foreach ($properties as $property) {
			/** @var ReflectionProperty $property */
			if (array_key_exists($property->getName(),$propertiesValuesArray)) {
				$property->setValue($instance, $propertiesValuesArray[$property->getName()]);
			} else {
				$property->setValue($instance, new UndefinedValue());
			}
		}
	}
}
