<?php

/**
 * GraphQL type registry which auto-discovers type definitions under a specified directory
 * and namespace. It also automatically passes the Query to each created type instance.
 */
class AutomatedTypeRegistry implements TypeRegistryInterface
{
    /**
     * Existing instances of types.
     *
     * @var object[]
     */
    protected $instancedTypes = [];

    /**
     * Directory of the auto-generated types. Used for locating types below the base namespace (e.g. Inputs/TypeName
     * instead of InputTypeName)
     *
     * @var string
     */
    protected $typesDirectory;

    /**
     * Namespace of the auto-generated types.
     *
     * @var string
     */
    protected $typesNamespace;

    /**
     * @var QueryResolver
     */
    protected $queryResolver;

    /**
     * @param string $typesDirectory
     * @param string $typesNamespace
     */
    public function __construct($typesDirectory, $typesNamespace)
    {
        $this->typesDirectory = $typesDirectory;
        $this->typesNamespace = $typesNamespace;
    }

    /**
     * Gets the instance of a type named $typeName.
     *
     * @param string $typeName
     * @return object
     */
    public function getTypeNamed($typeName)
    {

    }

    /**
     * Fetches the query resolver for this TypeRegistry instance.
     *
     * @return QueryResolver
     */
    public function getQueryResolver()
    {
        return $this->queryResolver;
    }

    /**
     * @param QueryResolver $queryResolver
     */
    public function setQueryResolver(QueryResolver $queryResolver)
    {
        $this->queryResolver = $queryResolver;
    }

    /**
     * Returns the base root query type.
     *
     * @return mixed
     */
    public function getQueryType()
    {
        return $this->getTypeNamed('query');
    }

    /**
     * Returns the base root mutation type.
     *
     * @return mixed
     */
    public function getMutationType()
    {
        return $this->getTypeNamed('mutation');
    }

    public static function restoreFromCache(TypeRegistryCachedData $cachedData)
	{
		$inst = new self($cachedData->getTypesDirectory());


	}
}