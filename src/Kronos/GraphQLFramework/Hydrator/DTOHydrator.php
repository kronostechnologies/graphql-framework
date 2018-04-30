<?php


namespace Kronos\GraphQLFramework\Hydrator;


use ArgumentCountError;
use function array_key_exists;
use Kronos\GraphQLFramework\Hydrator\Exception\DTORequiresArgumentsException;
use ReflectionClass;
use ReflectionProperty;

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
		$reflectionClass = new ReflectionClass($className);
		$properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);

		try {
			$instance = $reflectionClass->newInstance();
		} catch (ArgumentCountError $ex) {
			throw new DTORequiresArgumentsException($className);
		}

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
