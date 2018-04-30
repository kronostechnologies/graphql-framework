<?php


namespace Kronos\GraphQLFramework\Hydrator;


use function array_key_exists;
use Kronos\GraphQLFramework\Hydrator\Definition\BaseDTODefinition;
use ReflectionClass;
use ReflectionProperty;

class DTOHydrator
{
	/**
	 * @param string $className
	 * @param array $values
	 * @return mixed
	 */
	public function fromSimpleArray($className, array $values)
	{
		$reflectionClass = new ReflectionClass($className);
		$properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

		$instance = new $className();
		foreach ($properties as $property) {
			/** @var ReflectionProperty $property */
			if (array_key_exists($property->getName(),$values)) {
				$property->setValue($instance, $values[$property->getName()]);
			} else {
				$property->setValue($instance, new UndefinedValue());
			}
		}

		return $instance;
	}
}
