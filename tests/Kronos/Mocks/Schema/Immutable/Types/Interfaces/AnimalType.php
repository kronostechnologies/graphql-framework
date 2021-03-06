<?php

namespace Kronos\Mocks\Schema\Immutable\Types\Interfaces;

use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;

class AnimalType extends InterfaceType
{
	public function __construct($typeRegistry, $queryResolver)
	{
		parent::__construct([
			'name' => 'Animal',
			'fields' => [
				'id' => [
					'type' => Type::nonNull(Type::id())
				],
				'name' => [
					'type' => Type::nonNull(Type::string())
				],
				'ageYears' => [
					'type' => Type::int()
				]
			],
			'resolveType' => function ($value) use ($queryResolver) {
				return $queryResolver->resolveInterfaceType($value, 'Animal');
			}
		]);
	}
}