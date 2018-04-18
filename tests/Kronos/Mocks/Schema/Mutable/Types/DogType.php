<?php

namespace Kronos\Mocks\Schema\Mutable\Types;

use GraphQL\Type\Definition\ObjectType;

class DogType extends ObjectType
{
	public function __construct($typeRegistry, $queryResolver)
	{
		parent::__construct([
			'name' => 'Dog',
			'fields' => [
			],
			'interfaces' => [
				$typeRegistry->getTypeByName('Animal')
			]
		]);
	}
}