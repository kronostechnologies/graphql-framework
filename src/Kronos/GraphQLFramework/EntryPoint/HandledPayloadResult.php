<?php


namespace Kronos\GraphQLFramework\EntryPoint;


class HandledPayloadResult
{
    /**
     * @var string
     */
    protected $query;

    /**
     * @var array
     */
    protected $variables;

    /**
     * @param string $query
     * @param array $variables
     */
    public function __construct($query, array $variables)
    {
        $this->query = $query;
        $this->variables = $variables;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }
}