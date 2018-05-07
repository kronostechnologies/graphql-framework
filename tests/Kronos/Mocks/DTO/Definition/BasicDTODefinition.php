<?php


namespace Kronos\Mocks\DTO\Definition;


use Kronos\GraphQLFramework\Hydrator\Definition\BaseDTODefinition;
use Kronos\Mocks\DTO\BasicDTO;

class BasicDTODefinition extends BaseDTODefinition
{
	public function __construct()
	{
		$this->dtoDefinition = [
			'fqn' => BasicDTO::class
		];
	}
}
