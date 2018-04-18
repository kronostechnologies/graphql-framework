<?php


namespace Kronos\GraphQLFramework\TypeRegistry;


use GraphQL\Type\Definition\Type;

class DiscoveredType
{
	/**
	 * @var string
	 */
	protected $typeName;

	/**
	 * @return Type
	 */
	protected $typeInstance;

	/**
	 * @param string $typeName
	 * @param $typeInstance
	 */
	public function __construct($typeName, $typeInstance)
	{
		$this->typeName = $typeName;
		$this->typeInstance = $typeInstance;
	}

	/**
	 * @return string
	 */
	public function getTypeName()
	{
		return $this->typeName;
	}

	/**
	 * @return Type
	 */
	public function getTypeInstance()
	{
		return $this->typeInstance;
	}
}