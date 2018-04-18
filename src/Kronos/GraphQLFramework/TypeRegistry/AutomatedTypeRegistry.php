<?php


namespace Kronos\GraphQLFramework\TypeRegistry;

use GraphQL\Type\Definition\Type;

/**
 * Auto-detects types from the specified directory.
 */
class AutomatedTypeRegistry
{
	/**
	 * Get type by name. Throws an exception if the type was not found.
	 * @param string $typeName
	 */
	public function getTypeByName($typeName)
	{

	}

	/**
	 * Returns true if the type exists.
	 * @param string $typeName
	 */
	public function doesTypeExist($typeName)
	{

	}

	/**
	 * Helper function to fetch query type. Throws an exception if not found as it must always be provided as per the RFC.
	 * @return Type
	 */
	public function getQueryType()
	{

	}

	/**
	 * Helper function fetch mutation type. Can return null as per the RFC, which means mutations are not supported.
	 * @return Type|null
	 */
	public function getMutationType()
	{

	}
}