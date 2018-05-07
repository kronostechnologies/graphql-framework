<?php


namespace Kronos\Mocks\DTO\Definition;


use Kronos\GraphQLFramework\Hydrator\Definition\BaseDTODefinition;
use Kronos\Mocks\DTO\DepthDTO;
use Kronos\Mocks\DTO\DepthSubDTO;

class DepthDTODefinition extends BaseDTODefinition
{
	public function __construct()
	{
		$this->dtoDefinition = [
			'fqn' => DepthDTO::class,
			'fields' => [
				'subField' => DepthSubDTO::class
			]
		];
	}
}
