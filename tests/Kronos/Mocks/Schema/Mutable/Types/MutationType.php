<?php

namespace Kronos\Mocks\Schema\Mutable\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Kronos\Mocks\Schema\Mutable\TypeStore;

class MutationType extends ObjectType
{
	public function __construct($typeRegistry, $queryResolver)
	{
		parent::__construct([
			'name' => 'Mutation',
			'fields' => [
				'addDog' => [
					'type' => $typeRegistry->getTypeByName('Dog'),
					'args' => [
						'name' => [
							'type' => Type::nonNull(Type::string())
						],
						'age' => [
							'type' => TypeStore::number()
						]
					],
					'resolve' => function ($root, $args) use ($queryResolver) {
						return $queryResolver->resolveFieldOfType($root, $args, 'Mutation', 'addDog');
					}
				],
				'addCat' => [
					'type' => $typeRegistry->getTypeByName('Cat'),
					'args' => [
						'name' => [
							'type' => Type::nonNull(Type::string())
						],
						'age' => [
							'type' => TypeStore::number()
						]
					],
					'resolve' => function ($root, $args) use ($queryResolver) {
						return $queryResolver->resolveFieldOfType($root, $args, 'Mutation', 'addCat');
					}
				],
				'deleteAnimal' => [
					'type' => Type::nonNull(Type::boolean()),
					'args' => [
						'id' => [
							'type' => Type::nonNull(Type::id())
						]
					],
					'resolve' => function ($root, $args) use ($queryResolver) {
						return $queryResolver->resolveFieldOfType($root, $args, 'Mutation', 'deleteAnimal');
					}
				]
			]
		]);
	}
}