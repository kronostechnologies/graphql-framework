<?php


namespace Kronos\GraphQLFramework\Resolver\Deferred\Extractor;


use Kronos\GraphQLFramework\Resolver\Deferred\Store\DeferredValue;
use Kronos\GraphQLFramework\Resolver\Deferred\Store\DeferredValueStore;

class StorePopulator
{
    /**
     * @var DeferredValueStore
     */
    protected $store;

    /**
     * @param DeferredValueStore $store
     */
    public function __construct(DeferredValueStore $store)
    {
        $this->store = $store;
    }

    /**
     * @param $entity
     */
    public function populate($entity)
    {
        if (is_object($entity)) {
            foreach ($entity as $propertyName => $value) {
                if ($value instanceof DeferredValue) {
                    $this->store->addToGroup($value->getTypeName(), $value->getAttributedId());
                } else if (is_object($value)) {
                    $this->populate($value);
                }
            }
        }
    }

    /**
     * @return DeferredValueStore
     */
    public function getStore()
    {
        return $this->store;
    }
}