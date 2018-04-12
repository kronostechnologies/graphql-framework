<?php

/**
 * Describes a GraphQL controller, known as a Resolver in the last iteration of the GraphQL framework.
 * This class is responsible of fully expanding a specified field for the resolve functions of the GraphQL
 * library.
 *
 * Classes created from this should have the name of the type written in CamelCase, followed by Controller.
 *      i.e. a GraphQL type named newContact should have a controller called NewContactController
 *
 * For simple types without any complex field members, do not create a controller, since it will have no use.
 */
abstract class BaseController
{
    const PREFIX_GET_FIELD_FUNC = 'get';

    /**
     * @var GraphQLContext
     */
    private $context;

    /**
     * BaseController constructor.
     * @param GraphQLContext $context
     */
    public function __construct(GraphQLContext $context)
    {
        $this->context = $context;
    }

    /**
     * Returns the name of the function that should be executed for resolving a field. It does not check if the
     * function exists.
     *
     * @param string $fieldName
     * @return string
     */
    public static function getFieldMemberQueryFunctionName($fieldName)
    {
        return "";
    }

    /**
     * Immutable GraphQL context.
     *
     * @return GraphQLContext
     */
    protected function getContext()
    {
        return $this->context;
    }
}