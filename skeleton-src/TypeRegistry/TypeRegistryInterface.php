<?php

/**
 * Defines a TypeRegistry to be implemented for the base types.
 */
interface TypeRegistryInterface
{
    /**
     * Gets the instance of a type named $typeName. Only a single instance of the type
     * can exist per TypeRegistry!
     *
     * @param string $typeName
     * @return object
     */
    public function getTypeNamed($typeName);

    /**
     * Fetches the query resolver for this TypeRegistry instance.
     *
     * @return QueryResolver
     */
    public function getQueryResolver();

    /**
     * Sets the query resolver to be used by this TypeRegistry instance. It is the only way
     * for the types to talk with the actual framework.
     *
     * @param QueryResolver $queryResolver
     */
    public function setQueryResolver(QueryResolver $queryResolver);

    /**
     * Returns the base root query type.
     *
     * @return mixed
     */
    public function getQueryType();

    /**
     * Returns the base root mutation type.
     *
     * @return mixed
     */
    public function getMutationType();
}