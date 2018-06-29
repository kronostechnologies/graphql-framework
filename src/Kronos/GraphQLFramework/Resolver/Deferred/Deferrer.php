<?php


namespace Kronos\GraphQLFramework\Resolver\Deferred;


use Kronos\GraphQLFramework\Resolver\Deferred\Store\DeferredValue;

class Deferrer
{
    /**
     * Creates a DeferredValue class to be replaced after a complete call at the current level of the
     * GraphQL query is done.
     *
     * @param string $typeName
     * @param int $id
     * @return DeferredValue
     */
    public function defer($typeName, $id)
    {
        return new DeferredValue($typeName, $id);
    }
}