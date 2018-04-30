<?php


namespace Kronos\GraphQLFramework\Hydrator;


use ArgumentCountError;
use Kronos\GraphQLFramework\Hydrator\Definition\BaseDTODefinition;
use Kronos\GraphQLFramework\Hydrator\Exception\DTORequiresArgumentsException;
use Kronos\GraphQLFramework\Hydrator\Exception\FQNDefinitionMissingException;
use Kronos\GraphQLFramework\Hydrator\Exception\InvalidDefinitionClassException;
use Kronos\GraphQLFramework\Hydrator\Exception\InvalidFieldValueException;
use ReflectionClass;
use ReflectionProperty;
use function array_key_exists;
use function gettype;
use function is_array;
use function is_string;

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

	/**
	 * @param string|array|BaseDTODefinition $definition
	 * @param array $values
	 * @throws InvalidDefinitionClassException
	 */
	public function fromDTODefinition($definition, array $values)
	{
		$definition = $this->getDefinitionArrayFromValue($definition);

		if (!array_key_exists('fqn', $definition)) {
			throw new FQNDefinitionMissingException();
		}

		return $this->createInstanceFromDefinition($definition, $values);
	}

	protected function createInstanceFromDefinition($definition, $values)
	{
		if (!array_key_exists('fqn', $definition)) {
			throw new FQNDefinitionMissingException();
		}

		$fqn = $definition['fqn'];
		$instance = $this->createDTOInstance($fqn);

		$depthProperties = $this->getDepthPropertiesFromDefinition($definition);

		$reflectionClass = new ReflectionClass($instance);
		$properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

		foreach ($properties as $property) {
			/** @var ReflectionProperty $property */
			if (array_key_exists($property->getName(), $values)) {
				if (array_key_exists($property->getName(), $depthProperties)) {
					$subDefinition = $depthProperties[$property->getName()];
					$propertyValues = $values[$property->getName()];

					if (!is_array($propertyValues)) {
						throw new InvalidFieldValueException($property->getName(), $fqn, gettype($propertyValues));
					}

					$property->setValue($instance, $this->createInstanceFromDefinition($subDefinition, $propertyValues));
				} else {
					$property->setValue($instance, $values[$property->getName()]);
				}
			} else {
				$property->setValue($instance, new UndefinedValue());
			}
		}

		return $instance;
	}

	protected function getDepthPropertiesFromDefinition($definition)
	{
		$depthProperties = [];

		if (array_key_exists('fields', $definition)) {
			foreach ($definition['fields'] as $propertyName => $value) {
				if (!is_array($value)) {
					$value = ['fqn' => $value];
				}

				$depthProperties[$propertyName] = $value;
			}
		}

		return $depthProperties;
	}

	/**
	 * @param string|array|BaseDTODefinition $definition
	 * @return array
	 * @throws InvalidDefinitionClassException
	 */
	protected function getDefinitionArrayFromValue($definition)
	{
		if (is_string($definition)) {
			$definition = new $definition;
		}

		if ($definition instanceof BaseDTODefinition) {
			$definition = $definition->getDtoDefinition();
		}

		if (!is_array($definition)) {
			throw new InvalidDefinitionClassException(get_class($definition));
		}

		return $definition;
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
