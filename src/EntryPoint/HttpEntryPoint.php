<?php

/**
 * Acts as an entry point to the GraphQL service layer for HTTP requests. Takes a PSR-7 object as
 * an entry point, along with a PSR-3 logger. The logger is given the job of filtering what's unimportant
 * for it.
 *
 * After processing a query from this entry-point a PSR-7 HTTP response object will be returned.
 */
class HttpEntryPoint
{
    /**
     * @var GraphQLConfiguration
     */
    protected $configuration;

    /**
     * @var QueryResolver
     */
    protected $controllerFactory;

    /**
     * @param GraphQLConfiguration $configuration
     */
    public function __construct(GraphQLConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Processes a PSR-7 HTTP request, and returns a PSR-7 HTTP response or an adequate HTTP exception
     * to be displayed to an end-user.
     *
     * @param mixed $psr7Request
     * @param GraphQLConfiguration $configuration
     */
    public static function processPSR7Request($psr7Request, GraphQLConfiguration $configuration)
    {

    }

}