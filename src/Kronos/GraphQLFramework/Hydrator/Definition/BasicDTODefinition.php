<?php


namespace Kronos\GraphQLFramework\Hydrator\Definition;

/**
 * Defines a basic DTO definition, which simply contains a root DTO.
 * @package Kronos\GraphQLFramework\Hydrator\Definition
 */
class BasicDTODefinition extends BaseDTODefinition
{
	/**
	 * @param string $dtoFQN
	 */
	public function __construct($dtoFQN)
	{
		$this->dtoDefinition = [
			'root' => $dtoFQN
		];
	}
}
