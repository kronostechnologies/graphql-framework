<?php

/**
 * Full context of the ongoing GraphQL query. Most importantly, this contains the authentication
 * and authorization details of the user. It also contains the latest resolved field.
 */
class GraphQLContext
{
    /**
     * Parent object of where we are at in the query. It is the value of $root returned directly by the
     * GraphQL library.
     *
     * To better understand, the GraphQL library will resolve this query in the following way:
     * query {
     *    contacts {
     *       id
     *       address {
     *         name
     *       }
     *    }
     * }
     *
     * First, it will call the resolve function "resolveContacts" with no $root value set (as it has no parent).
     * The function will then return a value. This value will be retained by the "resolveAddress" function in the
     * $root value. Practically, you want to fetch the contact and put it in a DTO at the "resolveContacts" step. At
     * "resolveAddress", $root->address should contain the address ID to load from the database or service layer.
     *
     * @var mixed|null
     */
    protected $currentRootContext;

    /**
     * Session of the active query.
     *
     * @var SessionContext
     */
    protected $session;

    /**
     * Full query text received from the client.
     *
     * @var string
     */
    protected $queryText;

    /**
     * Arguments received from the client.
     *
     * @var array
     */
    protected $currentArguments;

    /**
     * @param string $queryText
     * @param SessionContext $session
     */
    public function __construct($queryText, SessionContext $session)
    {
        $this->queryText = $queryText;
        $this->session = $session;
    }

    /**
     * @return mixed|null
     */
    public function getCurrentRootContext()
    {
        return $this->getCurrentRootContext();
    }

    /**
     * @return SessionContext
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return string
     */
    public function getQueryText()
    {
        return $this->queryText;
    }

    /**
     * @return array
     */
    public function getCurrentArguments()
    {
        return $this->currentArguments;
    }

    /**
     * Clones the current GraphQL context, sets its clone's arguments to the given value, and
     * returns it.
     *
     * @param mixed|null $arguments
     * @return GraphQLContext
     */
    public function withCurrentArguments(array $arguments)
    {
        $clonedInstance = clone $this;
        $clonedInstance->currentArguments = $arguments;

        return $clonedInstance;
    }

    /**
     * Clones the current GraphQL context, sets its clone's currentRootContext to the given value, and
     * returns it.
     *
     * @param mixed|null $rootContext
     * @return GraphQLContext
     */
    public function withCurrentRootContext($rootContext)
    {
        $clonedInstance = clone $this;
        $clonedInstance->currentRootContext = $rootContext;

        return $clonedInstance;
    }
}