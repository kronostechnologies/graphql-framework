<?php


namespace Kronos\GraphQLFramework\Hydrator\Definition;

/**
 * Defines a DTO definition. The $dtoDefinition argument should be filled by the inheriting class constructor.
 * This class is nothing more than a configuration to tell the DTOHydrator how to fill its values off an array.
 */
abstract class BaseDTODefinition
{
	/**
	 * @var array
	 */
	protected $dtoDefinition = [];

	/**
	 * @return array
	 */
	public function getDtoDefinition()
	{
		return $this->dtoDefinition;
	}
}
