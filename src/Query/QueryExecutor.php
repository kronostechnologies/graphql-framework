<?php

/**
 * Initializes the essential framework services, and executes the query itself.
 */
class QueryExecutor
{
    /**
     * @var GraphQLConfiguration
     */
    protected $configuration;

    /**
     * @var Query
     */
    protected $queryResolver;

    public function __construct(GraphQLConfiguration $configuration)
    {
        $this->configuration = $configuration;
        $this->queryResolver = QueryResolver::newFromConfiguration($this->configuration);
    }

    /**
     * @param string $queryString
     * @param string[] $queryArguments
     * @param GraphQLConfiguration $configuration
     */
    public function execute($queryString, $queryArguments, GraphQLConfiguration $configuration)
    {

    }
}