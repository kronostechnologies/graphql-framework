<?php


namespace Kronos\Mocks\DTO\Definition;


use Kronos\GraphQLFramework\Hydrator\Definition\BaseDTODefinition;

class MissingFQNBasicDTODefinition extends BaseDTODefinition
{
	public function __construct()
	{
		$this->dtoDefinition = [];
	}
}
