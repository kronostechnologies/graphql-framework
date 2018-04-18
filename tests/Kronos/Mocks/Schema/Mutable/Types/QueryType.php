<?php

namespace Kronos\Mocks\Schema\Mutable\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
	public function __construct($typeRegistry, $queryResolver)
	{
		parent::__construct([
			'name' => 'Query',
			'fields' => [
				'pet' => [
					'type' => $typeRegistry->getTypeByName('Animal'),
					'args' => [
						'id' => [
							'type' => Type::nonNull(Type::id())
						]
					],
					'resolve' => function ($root, $args) use ($queryResolver) {
						return $queryResolver->resolveFieldOfType($root, $args, 'Query', 'pet');
					}
				],
				'pets' => [
					'type' => Type::listOf(Type::nonNull($typeRegistry->getTypeByName('Animal'))),
					'resolve' => function ($root, $args) use ($queryResolver) {
						return $queryResolver->resolveFieldOfType($root, $args, 'Query', 'pets');
					}
				]
			]
		]);
	}
}