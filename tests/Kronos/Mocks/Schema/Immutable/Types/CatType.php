<?php

namespace Kronos\Mocks\Schema\Immutable\Types;

use GraphQL\Type\Definition\ObjectType;

class CatType extends ObjectType
{
	public function __construct($typeRegistry, $queryResolver)
	{
		parent::__construct([
			'name' => 'Cat',
			'fields' => [
			],
			'interfaces' => [
				$typeRegistry->getTypeByName('Animal')
			]
		]);
	}
}